<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Services\VisitCalendarService;
use App\Models\MissionRun;
use App\Models\mission;
use App\Models\Hospital;
use App\Models\installbase;
use App\Models\InstallbaseUpdateLog;
use App\Models\MissionHistory;
use App\Models\MissionValidationList;
use App\Models\Prospect;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;

use function Symfony\Component\String\u;

class MissionRunController extends Controller
{
   public function store(Request $request)
  {
    $request->validate([
      'hospital_id' => ['required','integer'],
      'deadline_mission' => ['nullable','date'],
      'person_in_charge' => ['nullable','integer'],
    ]);

    // must be hospital that has available tasks in pool (status 0)
    $hasTasks = mission::where('status_mission', 0)
      ->where('hospital_id', $request->hospital_id)
      ->exists();

    if (!$hasTasks) {
      return response()->json(['message' => 'No available task pool for this hospital.'], 422);
    }

    $run = MissionRun::create([
      'code' => MissionRun::makeCode(),
      'hospital_id' => $request->hospital_id,
      'creator_id' => auth()->id(),
      'deadline_mission' => $request->deadline_mission,
      'status_mission' => 1, // active
      'validate_mission' => 0,
      'person_in_charge' => $request->person_in_charge,
      'check_in_id' => null,
      'check_out_id' => null,
    ]);

    return response()->json([
      'message' => 'Mission created',
      'data' => $run,
    ]);
  }

  public function byHospital($hospitalId)
  {
    $runs = MissionRun::query()->with('hospital')
      ->where('hospital_id', $hospitalId)
      ->whereIn('status_mission', [0,1,2,3]) //0=draft, 1=active,2=runs,3=under-validation,4=cancelled/deny,5=completed
      ->orderByDesc('id')
      ->get();


    return response()->json(['data' => $runs]);
  }


  public function bulkAddToMissionRun(Request $request)
  {
    $request->validate([
      'mission_run_id' => ['required','integer'],
      'task_ids' => ['required','array','min:1'],
      'task_ids.*' => ['integer'],
    ]);

    $run = MissionRun::findOrFail($request->mission_run_id);

    $tasks = mission::whereIn('id', $request->task_ids)->get();

    if ($tasks->isEmpty()) {
      return response()->json(['message' => 'No task selected.'], 422);
    }

    // enforce: all tasks must be status 0
    if ($tasks->contains(fn($t) => (int)$t->status_mission !== 0)) {
    return response()->json([
        'message' => 'Some tasks are not in task pool (status 0).'
    ], 422);
}

    // enforce: all tasks same hospital
    $uniqueHospitals = $tasks->pluck('hospital_id')->unique()->values();
    if ($uniqueHospitals->count() !== 1) {
      return response()->json(['message' => 'Selected tasks must be from the same hospital.'], 422);
    }

    // enforce: same hospital as mission header
    if ((int)$uniqueHospitals->first() !== (int)$run->hospital_id) {
      return response()->json(['message' => 'Tasks hospital must match selected mission hospital.'], 422);
    }

    // attach tasks to mission header + move to mission pool
    foreach ($tasks as $t) {
      $t->mission_run_id = $run->id;
      $t->status_mission = 1;   // mission pool
      // do NOT set PIC here (you said PIC is on mission header / later)
      //send pic if any from request, but do not override if already set on task
      if ($request->person_in_charge && !$t->person_in_charge) {
        $t->person_in_charge = $request->person_in_charge;
      }
      $t->save();
    }

    return response()->json([
      'message' => 'Tasks added to mission',
      'count' => $tasks->count(),
    ]);
  }

    public function getRunTasks(MissionRun $run)
    {
        $run->load(['hospital:id,name']);

        $tasks = mission::with(['departmentRelation'])
            ->where('mission_run_id', $run->id)
            ->get();



        return view('tabs._mission_run_tasks_table', [
            'run' => $run,
            'tasks' => $tasks,
        ]);
    }

    public function addTasksToRun(Request $request, MissionRun $run)
    {
        mission::whereIn('id', $request->task_ids)
            ->update([
                'mission_run_id' => $run->id,
                'status_mission' => 2,
                'pic_user_id' => $run->person_in_charge
            ]);

        return response()->json(['ok' => true]);
    }

    public function removeTaskFromRun(mission $task)
    {
        if ((int)$task->status_mission >= 6) {
            return response()->json(['error' => 'Cannot remove'], 400);
        }

        $task->update([
            'mission_run_id' => null,
            'status_mission' => 0,
            'schedule_date' => null,
            'schedule_time' => null,
        ]);

        return response()->json(['ok' => true]);
    }

    public function getTaskReference(mission $task)
    {
        switch (strtolower($task->task_reference ?? '')) {
            case 'installbase':
                $installbase = \App\Models\Installbase::with([
                    'hospital.province',
                    'product.brand',
                    'product.category',
                ])->find($task->code_ref);

                return view('tabs.reference._installbase', compact('task', 'installbase'));

            case 'prospect':
                $prospect = \App\Models\Prospect::with([
                    'hospital.province',
                    'department',
                    'unit',
                    'config',
                    'review',
                    'temperature',
                ])->find($task->code_ref);

                return view('tabs.reference._prospect', compact('task', 'prospect'));

            case 'custom':
                return view('tabs.reference._custom', compact('task'));

            case 'mapping':
                return view('tabs.reference._mapping', compact('task'));

            case 'finance':
            case 'salesadmin':
                return view('tabs.reference._finance', compact('task'));

            default:
                return '<div class="text-muted">Reference not prepared yet.</div>';
        }
    }


    public function approveVisit(MissionRun $run)
    {
        $role = strtolower(auth()->user()->role ?? '');

        if (!in_array($role, ['admin', 'am', 'nsm'])) {
            return redirect()->back()->with('error', 'You are not allowed to approve this visit.');
        }

        if ((int)$run->is_approve === 1) {
            return redirect()->back()->with('error', 'This visit is already approved.');
        }

        $run->is_approve = 1;
        $run->approved_by = auth()->id();
        $run->approved_at = now();
        $run->save();

        return redirect()->back()->with('success', 'Visit approved successfully.');
    }


    public function start(Request $request, MissionRun $run)
    {
        // optional: only scheduled can start
        // if ((int)$run->status_run !== 2) ...

        // make sure there is at least 1 task inside run
        $taskCount = mission::where('mission_run_id', $run->id)->count();
        if ($taskCount < 1) {
            return response()->json(['message' => 'No task in this mission. Add task first.'], 422);
        }

        // move run to on-progress
        $run->status_mission = 3; // on progress
        $run->status = 3; // on progress
        $run->started_at = now();
        $run->started_by = auth()->id();
        $run->save();

        // (optional) log history for run start
        // $this->logRunChange($run->id, 'started', ...)

        return response()->json([
            'message' => 'Mission started',
            'redirect' => route('missions.runs.show', $run->id),
        ]);
    }

    private function applyVisitAreaScope($query)
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');
        $area = optional($user->employee)->area;

        // HO / admin / BU can see all
        if ($area === 'HO' || in_array($role, ['admin', 'bu'])) {
            return $query;
        }

        if (!$area) {
            return $query->whereRaw('1 = 0');
        }

        return $query->whereHas('hospital.province', function ($q) use ($role, $area) {
            if ($role === 'fs') {
                $q->where('prov_order_no', $area);
            } elseif ($role === 'am') {
                $q->where('iss_area_code', $area);
            } elseif ($role === 'nsm') {
                $q->where('wilayah', $area);
            } else {
                $q->whereRaw('1 = 0');
            }
        });
    }

    public function show(Request $request, MissionRun $run)
    {
         $validationMode = (int) $request->get('validation_mode', 0);
        $run->load(['checkIn', 'checkOut']);

        // dd($run->checkIn, $run->checkOut);
        $hasCheckIn = !is_null($run->checkIn);
        $hasCheckOut = !is_null($run->checkOut);


        $checkInPhotoShow = "NO PHOTO";
        if ($run->checkIn && $run->checkIn->photo_data) {
            $urlphoto = str_replace(
                "https://drive.google.com/uc?id=",
                "https://drive.google.com/thumbnail?id=",
                $run->checkIn->photo_data
            );

            $checkInPhotoShow = str_replace("&export=media", "", $urlphoto);
        }

        // left side: tasks already in mission (status 1) grouped by task_reference
       $inMission = mission::with(['hospital', 'departmentRelation'])
    ->where('mission_run_id', $run->id)
    ->whereIn('status_mission', [1,2,3,4,6,7])
    ->get()
    ->groupBy('task_reference');

$usedPairs = mission::where('mission_run_id', $run->id)
    ->get(['task_reference', 'code_ref'])
    ->map(function ($m) {
        return $m->task_reference . '-' . $m->code_ref;
    })
    ->toArray();

$taskPoolRaw = mission::with(['hospital', 'departmentRelation'])
    ->whereNull('mission_run_id')
    ->where('hospital_id', $run->hospital_id)
    ->whereIn('status_mission', [0,30])
    ->get();

$taskPool = $taskPoolRaw
    ->filter(function ($m) use ($usedPairs) {
        return !in_array($m->task_reference . '-' . $m->code_ref, $usedPairs);
    })
    ->values()
    ->groupBy('task_reference');



        $allVisitTasks = $inMission->flatten();
        $totalTaskCount = $allVisitTasks->count();
        $validatedTaskCount = $allVisitTasks->where('status_mission', 7)->count();






        return view('admin.mission_run_show', compact('run', 'inMission', 'taskPool','hasCheckIn',
        'hasCheckOut','checkInPhotoShow', 'totalTaskCount', 'validatedTaskCount', 'validationMode'));
    }

    public function requestTasks(Request $request, MissionRun $run)
    {
        $request->validate([
            'task_ids' => ['required','array','min:1'],
            'task_ids.*' => ['integer'],
        ]);

        // only tasks from SAME hospital, still in task pool (0)
        $tasks = mission::whereIn('id', $request->task_ids)
            ->where('status_mission', 0)
            ->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No valid tasks selected (must be status 0).'], 422);
        }

        $uniqueHospitals = $tasks->pluck('hospital_id')->unique()->values();
        if ($uniqueHospitals->count() !== 1) {
            return response()->json(['message' => 'Selected tasks must be from the same hospital.'], 422);
        }

        if ((int)$uniqueHospitals->first() !== (int)$run->hospital_id) {
            return response()->json(['message' => 'Selected tasks hospital must match this mission hospital.'], 422);
        }

        // mark requested
        mission::whereIn('id', $tasks->pluck('id'))
            ->update([
                'status_mission' => 30,
                // optional later:
                // 'updated_by' => auth()->id(),
            ]);

        return response()->json([
            'message' => 'Request submitted. Waiting approval.',
            'count' => $tasks->count(),
        ]);
    }

    public function scheduleMissionRun(Request $request) // not use to any
    {
    $validated = $request->validate([
        'run_id' => ['required','integer','exists:mission_runs,id'],
        'schedule_date' => ['required','date'],
        'schedule_time' => ['required','date_format:H:i'],
        'duration_minutes' => ['required','integer','in:60,120,180,240,300,360,420,480'],
    ]);

    $run = MissionRun::findOrFail($validated['run_id']);

    // PIC required rule (you said PIC is required)
    if (empty($run->person_in_charge)) {
        return response()->json([
        'message' => 'PIC is required before scheduling this mission.'
        ], 422);
    }

    $run->schedule_date = $validated['schedule_date'];
    $run->schedule_time = $validated['schedule_time'] . ':00';
    $run->schedule_duration_minutes = $validated['duration_minutes'];
    $run->status_mission = 2; // scheduled
    $run->status = 2; // scheduled
    $run->save();

    // OPTIONAL (recommended): move all tasks inside run to scheduled too
    // so "Start mission" can depend on tasks status easily
    mission::where('mission_run_id', $run->id)
        ->whereIn('status_mission', [1]) // in mission pool
        ->update([
        'pic_user_id' => $run->person_in_charge, // assign PIC from run header if not set on task
        'status_mission' => 2,
        'schedule_date' => $run->schedule_date,
        'schedule_time' => $run->schedule_time,
        'schedule_duration_minutes' => $run->schedule_duration_minutes,
        ]);

    return response()->json([
        'message' => 'Mission scheduled.',
    ]);
    }

    public function addRequestedToMission(Request $request, MissionRun $run)
    {
        // role guard sederhana (sesuaikan field role kamu)
        $role = auth()->user()->role ?? null;
        if (!in_array($role, ['admin','am','nsm'])) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        DB::transaction(function () use ($run, &$count) {
            $q = mission::where('hospital_id', $run->hospital_id)
                ->where('status_mission', 30);

            $count = (clone $q)->count();

            $q->update([
                'mission_run_id' => $run->id,
                'status_mission' => 1,
                'updated_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Requested tasks added to mission.',
            'count' => $count ?? 0,
        ]);
    }

    public function planVisit(Request $request)
    {
        $role = strtolower(auth()->user()->role ?? '');

        $rules = [
            'hospital_id' => ['required', 'integer'],
            'task_ids' => ['required', 'array', 'min:1'],
            'task_ids.*' => ['integer'],
            'schedule_date' => ['required', 'date'],
            'schedule_time' => ['required', 'date_format:H:i'],
            'schedule_duration_minutes' => ['required', 'integer', 'in:60,120,180,240,300,360,420,480'],
        ];

        if ($role !== 'fs') {
            $rules['pic_user_id'] = ['required', 'integer'];
        }

        $request->validate($rules);

        $tasks = mission::whereIn('id', $request->task_ids)->get();

        if ($tasks->isEmpty()) {
            return response()->json(['message' => 'No selected tasks found.'], 422);
        }

        // all selected tasks must be same hospital
        $uniqueHospitals = $tasks->pluck('hospital_id')->unique()->values();
        if ($uniqueHospitals->count() !== 1) {
            return response()->json(['message' => 'Selected tasks must be from the same hospital.'], 422);
        }

        if ((int)$uniqueHospitals->first() !== (int)$request->hospital_id) {
            return response()->json(['message' => 'Selected tasks hospital mismatch.'], 422);
        }

        // only task pool tasks
        if ($tasks->contains(fn($t) => (int)$t->status_mission !== 0)) {
            return response()->json(['message' => 'Some selected tasks are not in task pool (status 0).'], 422);
        }

        DB::beginTransaction();

        try {
            $run = new MissionRun();
            $run->code = MissionRun::makeCode(); // make helper below
            $run->hospital_id = $request->hospital_id;
            $run->creator_id = auth()->id();

            // schedule / deadline use same value for now
            $run->schedule_date = $request->schedule_date;
            $run->schedule_time = $request->schedule_time;
            $run->deadline_mission = $request->schedule_date;

            $run->status = 2; // mission pool / visit created
            $run->status_mission = 2; // if you still use this field too
            $run->person_in_charge = $role === 'fs' ? auth()->id() : $request->pic_user_id;
            $run->schedule_duration_minutes = $request->schedule_duration_minutes;
            $run->save();

            mission::whereIn('id', $tasks->pluck('id'))
                ->update([
                    'mission_run_id' => $run->id,
                    'status_mission' => 2,
                    'pic_user_id' => $run->person_in_charge, // assign PIC from run header
                    'schedule_date' => $request->schedule_date,
                    'schedule_time' => $request->schedule_time,
                    'schedule_duration_minutes' => $request->schedule_duration_minutes,
                    'updated_at' => now(),
                ]);

             $prospectIds = $tasks
                ->where('task_reference', 'prospect')
                ->pluck('code_ref')
                ->filter()
                ->unique()
                ->values();

            if ($prospectIds->isNotEmpty()) {
                \App\Models\Prospect::whereIn('id', $prospectIds)
                    ->update([
                        'pic_user_id' => $run->person_in_charge,
                        'updated_at' => now(),
                    ]);
            }



            DB::commit();

            return response()->json([
                'message' => 'Visit created and tasks added.',
                'run_id' => $run->id,
                'code' => $run->code,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to create visit.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function taskStart(mission $task)
    {
        //task start logic for visit detail
        // optional: mark task as on progress when opened
        // if ((int)$task->status_mission < 3) {
        //     $task->status_mission = 3;
        //     $task->updated_by = auth()->id();
        //     $task->save();
        // }

        switch (strtolower($task->task_reference ?? '')) {
            case 'prospect':
                $prospect = \App\Models\Prospect::with('temperature')->find($task->code_ref);

                if (!$prospect) {
                    return redirect()->back()->with('error', 'Prospect data not found.');
                }

                $stage = (int) optional($prospect->temperature)->tempCodeName;

                // Lead / Delayed Lead -> show selector first
                if (in_array($stage, [1, 7])) {
                    return redirect()->back()->with([
                        'open_lead_action_selector' => true,
                        'task_id' => $task->id,
                        'hospital_target'=> optional($task->hospital)->target,
                    ]);
                }

                // Promo -> go to promo update form
                if ($stage === 6) {
                    return redirect()->route('missions.task.promo.prospect', $task->id);
                }

                // Prospect / Funnel / Hot Prospect -> go to review prospect form
                if (in_array($stage, [2, 3, 4])) {
                    return redirect()->route('missions.task.prospect', $task->id);
                }

                // Drop / Missed / Success -> block
                if (in_array($stage, [0, -1, 5])) {
                    return redirect()->back()->with('error', 'This prospect/lead is already closed.');
                }

                return redirect()->back()->with('error', 'Prospect stage not recognized.');

            case 'installbase':
                return redirect()->route('missions.task.installbase', $task->id);

            case 'salesadmin':
            case 'finance':
                return redirect()->route('missions.task.finance', $task->id);

            case 'mapping':
                return redirect()->route('missions.task.mapping', $task->id);

            case 'custom':
                return redirect()->back()->with([
                    'open_custom_task_modal' => true,
                    'custom_task_id' => $task->id,
                ]);

            default:
                return redirect()->back()->with('error', 'Task reference page not prepared yet.');
        }
    }


    public function previewByTask(mission $task)
    {
        $validation = MissionValidationList::where('mission_id', $task->id)
            ->where('status', 0)
            ->latest()
            ->firstOrFail();
        $payload = json_decode($validation->payload_form, true) ?? [];

            $payloadUnit = null;
        $payloadConfig = null;

        if (!empty($payload['unit_id'])) {
            $payloadUnit = \App\Models\Unit::find($payload['unit_id']);
        }

        if (isset($payload['config_id']) && $payload['config_id'] !== '') {
            $payloadConfig = \App\Models\Config::find($payload['config_id']);
        }

        // dd($validation, $payload, $payloadUnit, $payloadConfig);

        switch (strtolower($validation->task_ref)) {
            case 'installbase':
                $installbase = \App\Models\Installbase::with([
                    'hospital.province',
                    'product.brand',
                    'product.category',
                ])->find($validation->code_ref);
                return view('admin.validation_preview_installbase', compact('task', 'validation', 'payload', 'installbase'));

            case 'prospect':
                $payload = json_decode($validation->payload_form, true) ?? [];
                $prospect = \App\Models\Prospect::with([
                    'hospital.province',
                    'department',
                    'unit',
                    'config',
                    'temperature',
                    'review',
                ])->find($validation->code_ref);

                $actionType = $payload['action_type'] ?? 'prospect_update';

                switch ($actionType) {
                    case 'drop':
                        return view('admin.validation_preview_lead_drop', compact(
                            'task', 'validation', 'payload', 'prospect', 'payloadUnit', 'payloadConfig'
                        ));

                    case 'delayed':
                        return view('admin.validation_preview_lead_delayed', compact(
                            'task', 'validation', 'payload', 'prospect', 'payloadUnit', 'payloadConfig'
                        ));

                    case 'promo':
                        return view('admin.validation_preview_lead_promo', compact(
                            'task', 'validation', 'payload', 'prospect', 'payloadUnit', 'payloadConfig'
                        ));

                    case 'lead_to_prospect':
                    case 'promo_to_prospect':
                    case 'prospect_update':
                    default:
                        return view('admin.validation_preview_prospect', compact(
                            'task', 'validation', 'payload', 'prospect', 'payloadUnit', 'payloadConfig'
                        ));
                }

            case 'mapping':
                return view('admin.validation_preview_mapping', compact('task', 'validation', 'payload'));
            case 'custom':
                return view('admin.validation_preview_custom', compact(
                    'task',
                    'validation',
                    'payload'
                ));
            case 'finance':
            case 'salesadmin':
                return view('admin.validation_preview_finance', compact('task', 'validation', 'payload'));

            default:
                return view('admin.validation_preview_generic', compact('task', 'validation', 'payload'));
        }
    }



    // public function approve(MissionValidationList $validation)
    // {
    //     if ((int)$validation->status === 1) {
    //         return redirect()->back()->with('error', 'This validation item is already processed.');
    //     }

    //     DB::beginTransaction();

    //     try {
    //         $task = mission::findOrFail($validation->mission_id);
    //         $payload = json_decode($validation->payload_form, true) ?? [];

    //         switch (strtolower($validation->task_ref)) {
    //             case 'installbase':
    //                 $this->installbaseDataUpdate($validation, $task, $payload);
    //                 break;

    //             case 'prospect':
    //                 $this->prospectDataUpdate($validation, $task, $payload);
    //                 break;

    //             case 'mapping':
    //                 $this->mappingDataUpdate($validation, $task, $payload);
    //                 break;

    //             case 'finance':
    //             case 'salesadmin':
    //                 $this->financeDataUpdate($validation, $task, $payload);
    //                 break;
    //         }



    //         // mark validation done
    //         $validation->validate_by = auth()->id();
    //         $validation->validate_at = now();
    //         $validation->status = 1;
    //         $validation->save();

    //         // mark mission/task done
    //         $task->status_mission = 7;
    //         $task->updated_by = auth()->id();
    //         if (!empty($payload['report_result'])) {
    //             $task->report_result = $payload['report_result'];
    //         }
    //         $task->save();

    //         // optional: mission history
    //         // MissionHistory::create([...])

    //         DB::commit();

    //         return redirect()->back()->with('success', 'Validation approved and data updated successfully.');
    //     } catch (\Throwable $e) {
    //         DB::rollBack();

    //         return redirect()->back()->with('error', 'Failed to approve validation: ' . $e->getMessage());
    //     }
    // }


    public function showProspectTask(mission $task)
    {
        $prospect = \App\Models\Prospect::with([
            'hospital.province',
            'department',
            'unit',
            'temperature',
            'review',
        ])->findOrFail($task->code_ref);

        // Corrected the method string and the array syntax

    return view('admin.mission_task_prospect', compact('task', 'prospect'));

    }

    public function updateProspectTask(Request $request, mission $task)
    {

        // $prospect = \App\Models\Prospect::with('review')->findOrFail($task->code_ref);

        $request->validate([
            'eta_po_date' => ['nullable', 'date'],
            'first_offer_date' => ['nullable', 'date'],
            'demo_date' => ['nullable', 'date'],
            'presentation_date' => ['nullable', 'date'],
            'last_offer_date' => ['nullable', 'date'],
            'user_status' => ['nullable', 'string'],
            'direksi_status' => ['nullable', 'string'],
            'purchasing_status' => ['nullable', 'string'],
            'anggaran_status' => ['nullable', 'string'],
            'jenis_anggaran' => ['nullable', 'string'],
            'chance' => ['nullable', 'numeric'],
            'comment' => ['nullable', 'string'],
            'next_action' => ['nullable', 'string'],
            'report_result' => ['required', 'string'],
        ]);



        DB::beginTransaction();

        try {
            $existing = MissionValidationList::where('mission_id', $task->id)
                ->where('status', 0)
                ->exists();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This task already has pending validation.');
            }

            $payload = [
                'eta_po_date' => $request->eta_po_date ?? null,
                'first_offer_date' => $request->first_offer_date ?? null,
                'demo_date' => $request->demo_date ?? null,
                'presentation_date' => $request->presentation_date ?? null,
                'last_offer_date' => $request->last_offer_date ?? null,
                'user_status' => $request->user_status ?? null,
                'direksi_status' => $request->direksi_status ?? null,
                'purchasing_status' => $request->purchasing_status ?? null,
                'anggaran_status' => $request->anggaran_status ?? null,
                'jenis_anggaran' => $request->jenis_anggaran ?? null,
                'chance' => $request->chance ?? null,
                'comment' => $request->comment ?? null,
                'next_action' => $request->next_action ?? null,
                'report_result' => $request->report_result ?? null,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];


            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->report_result = $request->report_result;
            $task->status_mission = 6; // on review
            $task->updated_by = auth()->id();
            $task->save();

            DB::commit();

            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Prospect review submitted for validation.');
        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }



    private function prospectDataUpdate($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with('review')->findOrFail($validation->code_ref);

        $review = $prospect->review;
        if (!$review) {
            $review = \App\Models\Review::create([
                'prospect_id' => $prospect->id,
            ]);
        }


        $fields = [
            'first_offer_date',
            'demo_date',
            'presentation_date',
            'last_offer_date',
            'user_status',
            'direksi_status',
            'purchasing_status',
            'anggaran_status',
            'jenis_anggaran',
            'chance',
            'comment',
            'next_action',
        ];

        foreach ($fields as $field) {

            $oldValue = $review->$field ?? "";
            $newValue = $payload[$field] ?? "";



            if (in_array($field, [
            'first_offer_date',
            'demo_date',
            'presentation_date',
            'last_offer_date',
            ])) {
                $oldCompare = $oldValue ? \Carbon\Carbon::parse($oldValue)->format('Y-m-d') : "New Data";
                $newCompare = $newValue ? \Carbon\Carbon::parse($newValue)->format('Y-m-d') : "New Data";
            } else {
                $oldCompare = is_null($oldValue) ? "New Data" : trim((string) $oldValue);
                $newCompare = is_null($newValue) ? "New Data" : trim((string) $newValue);
            }



            if ($oldCompare !== $newCompare) {

                \App\Models\ReviewLog::create([
                    'review_id' => $review->id,
                    'log_date' => now()->toDateString(),
                    'col_update' => $field,
                    'col_before' => $oldValue,
                    'col_after' => $newValue,
                    'updated_by' => auth()->id(),
                ]);

                $review->$field = $newValue;
            }
        }

        if (!empty($payload['validator_comment'])) {
            $oldComment = $review->comment ?? '';
            $newComment = trim($oldComment . "\n[Validator] " . $payload['validator_comment']);

            if ($oldComment !== $newComment) {
                \App\Models\ReviewLog::create([
                    'review_id' => $review->id,
                    'log_date' => now()->toDateString(),
                    'col_update' => 'validator_comment',
                    'col_before' => $oldComment,
                    'col_after' => $newComment,
                    'updated_by' => auth()->id(),
                ]);

                $review->comment = $newComment;
            }
        }


        $review->save();
    }



    public function showInstallbaseTask(mission $task)
    {
        $installbase = null;
        $department = null;

        if ($task->code_ref) {
            $installbase = installbase::with(['hospital.province', 'product'])
                ->where('id', $task->code_ref)
                ->first();

            if ($installbase && $installbase->department_id) {
                $department = Department::find($installbase->department_id);
            }
        }

        if (!$installbase) {
            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('error', 'Installbase data not found for this task.');
        }

        return view('admin.mission_task_installbase', compact('task', 'installbase', 'department'));

    }




    private function installbaseDataUpdate($validation, $task, array $payload): void
    {
        $installbase = installbase::findOrFail($validation->code_ref);

        $fields = [
            'department',
            'pic_to_recall',
            'department_phone',
            'serial_number',
            'installation_date',
            'installbase_status',
            'end_of_warranty',
            'label_photo',
        ];

        $taskUpdateNo = 'IBUPD-' . now()->format('ymdHis') . '-' . $task->id;
        $changed = false;

        foreach ($fields as $field) {
            $oldValue = $installbase->$field ?? null;
            $newValue = $payload[$field] ?? null;

            if (in_array($field, ['installation_date', 'end_of_warranty'])) {
                $oldValueCompare = $oldValue ? Carbon::parse($oldValue)->format('Y-m-d') : null;
                $newValueCompare = $newValue ? Carbon::parse($newValue)->format('Y-m-d') : null;
            } else {
                $oldValueCompare = is_null($oldValue) ? null : trim((string) $oldValue);
                $newValueCompare = is_null($newValue) ? null : trim((string) $newValue);
            }

            if ($oldValueCompare !== $newValueCompare) {
                $changed = true;

                InstallbaseUpdateLog::create([
                    'installbase_id' => $installbase->id,
                    'mission_id' => $task->id,
                    'task_update_no' => $taskUpdateNo,
                    'field_column' => $field,
                    'value_before' => $oldValue,
                    'new_value' => $newValue,
                    'updated_by' => auth()->id(),
                ]);

                $installbase->$field = $newValue;
            }
        }

        if ($changed) {
            $installbase->save();
        }
    }

    private function createNextProspectTask($task, array $payload): void
    {
        $nextAction = trim((string)($payload['next_action'] ?? ''));
        $action_type = trim((string)($payload['action_type'] ?? ''));


        $newTask = new mission();
        $newTask->code = mission::makeCode('prospect');

        $newTask->hospital_id = $task->hospital_id;
        $newTask->department = $task->department;
        $newTask->code_ref = $task->code_ref;
        $newTask->task_reference = 'prospect';

        $newTask->task_purpose = $nextAction;
        $newTask->task_creator_id = auth()->id();

        if($action_type === 'lead_to_prospect') {
            $newTask->generate_task_via = "prospect_from_lead";
        } elseif($action_type === 'promo_to_prospect') {
            $newTask->generate_task_via = 'prospect_from_promo';
        } else {
        $newTask->generate_task_via = 'prospect_review';}
        $newTask->deadline = now()->addWeeks(2)->toDateString();
        $newTask->priority_level = 'Urgent';
        $newTask->expected_outcome = $nextAction;
        $newTask->report_result = null;
        $newTask->status_mission = 0;
        $newTask->updated_by = null;
        $newTask->save();
    }

    public function updateInstallbaseTask(Request $request, mission $task)
    {

         $request->validate([
            'department' => 'nullable|string',
            'pic_to_recall' => 'nullable|string',
            'department_phone' => 'nullable|string',
            'serial_number' => 'nullable|string',
            'installation_date' => 'nullable|date',
            'installbase_status' => 'nullable|string',
            'end_of_warranty' => 'nullable|date',
            'report_result' => 'nullable|string',
            'label_photo' => 'nullable|string'
        ]);


        DB::beginTransaction();

        try {

        $payload = [
            'department' => $request->input('department'),
            'pic_to_recall' => $request->input('pic_to_recall'),
            'department_phone' => $request->input('department_phone'),
            'serial_number' => $request->input('serial_number'),
            'installation_date' => $request->input('installation_date'),
            'installbase_status' => $request->input('installbase_status'),
            'end_of_warranty' => $request->input('end_of_warranty'),
            'report_result' => $request->input('report_result'),
            'label_photo' => $request->input('label_photo'),
            'submitted_by' => auth()->id(),
            'submitted_at' => now()->toDateTimeString(),
        ];

        MissionValidationList::create([
            'mission_id' => $task->id,
            'task_ref' => $task->task_reference,
            'code_ref' => $task->code_ref,
            'payload_form' => json_encode($payload),
            'status' => 0,
        ]);

        $task->status_mission = 6; //waiting validation
        $task->report_result = $request->input('report_result');
        $task->updated_by = auth()->id();
        $task->save();

        DB::commit();
        return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Installbase update submitted for validation.');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to Submit : ' . $e->getMessage());
        }
    }



    public function showFinanceTask(mission $task)
    {
        //task complete logic for finance in visit detail
        return view('admin.mission_task_finance', compact('task'));
    }

    public function updateFinanceTask(Request $request, mission $task)
    {

    }
    public function financeDataUpdate($validation, $task, array $payload): void
    {

    }

    public function showMappingTask(mission $task)
    {
        //task complete logic for mapping in visit detail
        return view('admin.mission_task_mapping', compact('task'));
    }

    public function updateMappingTask(Request $request, mission $task)
    {

    }
    public function mappingDataUpdate($validation, $task, array $payload): void
    {

    }


    public function submitVisit(MissionRun $run)
    {

        $run->status= 6;
        $run->status_mission= 6;
        $run->save();

        return redirect()
                ->route('missions.pool')
                ->with('success', 'Visit submitted for validation.');
    }

    public function validateTask(mission $task, Request $request)
    {
        $run = MissionRun::findOrFail($task->mission_run_id);

        if ((int)$run->status !== 6) {
            return redirect()->back()->with('error', 'Visit must be submitted before task validation.');
        }

        $validation = MissionValidationList::where('mission_id', $task->id)
            ->where('status', 0)
            ->latest()
            ->firstOrFail();

        $payload = json_decode($validation->payload_form, true) ?? [];

        switch (strtolower($task->task_reference)) {

            case 'prospect':
                $actionType = $payload['action_type'] ?? null;

                switch ($actionType) {
                    case 'drop':
                    case 'delayed':
                        $request->validate([
                            'validator_comment' => ['required', 'string'],
                        ]);

                        $payload['validator_comment'] = $request->validator_comment;
                        break;
                    case 'promo':
                        $request->validate([
                            'validator_comment' => ['required', 'string'],
                        ]);

                        $payload['validator_comment'] = $request->validator_comment;
                        break;
                    case 'lead_to_prospect':

                        $request->validate([
                            'validator_comment' => ['required'],
                        ]);

                        $payload['validator_comment'] = $request->validator_comment;
                        $payload['next_action'] = $request->next_action;
                        break;
                    case 'promo_to_prospect':
                        $request->validate([
                            'validator_comment' => ['required', 'string'],
                        ]);

                        $payload['validator_comment'] = $request->validator_comment;
                        $payload['next_action'] = $request->next_action;
                        break;
                    default:
                        $request->validate([
                            'validator_comment' => ['required', 'string'],
                            'next_action' => ['required', 'string'],
                        ]);

                        $payload['validator_comment'] = $request->validator_comment;
                        $payload['next_action'] = $request->next_action;
                        break;
                }

                break;


            case 'installbase':

                $request->validate([
                    'validator_comment' => ['nullable', 'string'],
                ]);

                if ($request->filled('validator_comment')) {
                    $payload['validator_comment'] = $request->validator_comment;
                }

                break;


            case 'mapping':
            case 'finance':
            case 'salesadmin':
            case 'custom':

                $request->validate([
                    'validator_comment' => ['nullable', 'string'],
                ]);

                if ($request->filled('validator_comment')) {
                    $payload['validator_comment'] = $request->validator_comment;
                }

                break;

            default:
                return redirect()->back()->with('error', 'Task reference not supported for validation.');
        }

        // COMMON PART (applies to all task_ref)
        $payload['validated_by'] = auth()->id();
        $payload['validated_at'] = now()->toDateTimeString();

        $validation->payload_form = json_encode($payload);
        $validation->status = 1;
        $validation->validate_by = auth()->id();
        $validation->validate_at = now();
        $validation->save();

        $task->status_mission = 7; // wait finalize
        $task->updated_by = auth()->id();
        $task->save();

        return redirect()->back()->with('success', 'Task validated successfully.');
    }


    public function getAvailableTasksForRun(MissionRun $run)
    {
        $tasks = mission::with(['departmentRelation'])
            ->whereNull('mission_run_id')
            ->whereIn('status_mission', [0, 1]) // pool only
            ->where('hospital_id', $run->hospital_id)
            ->get();

        return view('tabs._available_tasks', compact('tasks', 'run'));
    }


    public function validateVisit(MissionRun $run)
    {
        // dd('masuk validateVisit', $run->id);
        DB::beginTransaction();

        try {
            $tasks = mission::where('mission_run_id', $run->id)->get();
            // dd('tasks loaded', $tasks->count(), $tasks->pluck('id'));
            if ($tasks->count() < 1) {
                return redirect()->back()->with('error', 'No task found in this visit.');
            }

            foreach ($tasks as $task) {

                // CASE 1: task already validated -> finalize normally
                if ((int)$task->status_mission === 7) {

                    $validation = MissionValidationList::where('mission_id', $task->id)
                        ->where('status', 1)
                        ->latest()
                        ->first();


                    if ($validation) {
                        $payload = json_decode($validation->payload_form, true) ?? [];

                        switch (strtolower($validation->task_ref)) {
                            case 'installbase':
                                $this->installbaseDataUpdate($validation, $task, $payload);
                                break;

                            case 'prospect':
                                $actionType = $payload['action_type'] ?? null;

                                switch ($actionType) {
                                    case 'drop':
                                        $this->prospectDropUpdate($validation, $task, $payload);
                                        break;

                                    case 'delayed':
                                        $this->prospectDelayedUpdate($validation, $task, $payload);
                                        $this->createDelayedProspectTask($task, $payload);
                                        break;

                                    case 'lead_to_prospect':

                                        $this->prospectLeadConvert($validation, $task, $payload);
                                        $this->createNextProspectTask($task, $payload);

                                    break;

                                    case 'promo':
                                        $this->prospectPromoUpdate($validation, $task, $payload);
                                        $this->createPromoProspectTask($task, $payload);
                                    break;

                                    case 'promo_to_prospect':
                                        $this->prospectFinalConvert($validation, $task, $payload);
                                        $this->createNextProspectTask($task, $payload);
                                    break;

                                    default:

                                        $this->prospectDataUpdate($validation, $task, $payload);

                                        $this->createNextProspectTask($task, $payload);
                                        break;
                                }
                                break;

                            case 'mapping':
                                $this->mappingDataUpdate($validation, $task, $payload);
                                break;

                            case 'finance':
                            case 'salesadmin':
                                $this->financeDataUpdate($validation, $task, $payload);
                                break;

                            case 'custom':
                                $this->customTaskFinalize($validation, $task, $payload);
                                break;
                        }

                        $validation->status = 2; // finalized/applied
                        $validation->save();
                    }

                    $before = $task->status_mission;

                    $task->status_mission = 5;
                    $task->updated_by = auth()->id();
                    $task->save();

                    $this->logMissionChange(
                        $task->id,
                        'visit_validated_done',
                        [
                            'status_mission' => ['from' => $before, 'to' => 5],
                        ],
                        'Task finalized through visit validation.'
                    );

                    continue;
                }
                // CASE 2: task not validated -> mark missed and create new follow-up task
                $before = $task->status_mission;

                $task->status_mission = -1; // using your requested status for miss
                $task->updated_by = auth()->id();
                $task->save();

                $newTask = $this->createMissingTaskFromMission($task);

                $this->logMissionChange(
                    $task->id,
                    'visit_validated_missed',
                    [
                        'status_mission' => ['from' => $before, 'to' => -1],
                    ],
                    'Task not validated during visit validation. Follow-up task created: ' . $newTask->code
                );
            }

            $run->status = 5;
            $run->status_mission = 5;
            $run->save();

            DB::commit();

            return redirect()
                ->route('missions.pool')
                ->with('success', 'Visit validated successfully. Validated tasks were completed, and unvalidated tasks were returned as new follow-up tasks.');
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to validate visit: ' . $e->getMessage());
        }
    }


    private function createMissingTaskFromMission(mission $task): mission
    {
        $newTask = new mission();

        $code=mission::makeCode($task->task_reference);
        $newTask->code = $code;
        $newTask->hospital_id = $task->hospital_id;
        $newTask->department = $task->department;
        $newTask->code_ref = $task->code_ref;
        $newTask->task_reference = $task->task_reference;
        $newTask->task_creator_id = auth()->id();
        $newTask->generate_task_via = 'missed_task';
        $newTask->deadline = now()->addDays(14)->toDateString();
        $newTask->priority_level = $task->priority_level;
        $newTask->expected_outcome = $task->expected_outcome;
        $newTask->status_mission = 0;
        $newTask->updated_by = auth()->id();

        // optional copied fields if you want
        $newTask->task_purpose = $task->task_purpose;
        $newTask->user_to_meet = $task->user_to_meet;
        $newTask->pic_user_id = null; // back to pool, no PIC
        $newTask->mission_run_id = null; // very important, detach from old visit

        $newTask->save();

        return $newTask;
    }

     private function logMissionChange($missionId, string $action, array $changes = [], ?string $note = null): void
    {
        MissionHistory::create([
        'mission_id' => $missionId,
        'actor_user_id' => auth()->id(),
        'action' => $action,
        // LONGTEXT must be string, so encode array -> JSON string
        'changes' => $changes ? json_encode($changes, JSON_UNESCAPED_UNICODE) : null,
        'note' => $note,
         ]);
    }

    public function submitCustomTask(Request $request)
    {
        $request->validate([
            'task_id' => ['required', 'integer', 'exists:missions,id'],
            'generic_report' => ['required', 'string'],
        ]);

        $task = mission::findOrFail($request->task_id);

        // save into validation list first
        MissionValidationList::create([
            'mission_id' => $task->id,
            'task_ref' => $task->task_reference,
            'code_ref' => $task->code_ref,
            'payload_form' => json_encode([
                'report_result' => $request->generic_report,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ]),
            'status' => 0,
        ]);

        $task->report_result = $request->generic_report;
        $task->status_mission = 6; // on review
        $task->updated_by = auth()->id();
        $task->save();

        return redirect()
            ->back()
            ->with('success', 'Custom task submitted for validation.');
    }

    private function customTaskFinalize($validation, $task, array $payload): void
    {
        // optional: log only
        $this->logMissionChange(
            $task->id,
            'custom_task_finalized',
            [
                'report_result' => [
                    'from' => $task->report_result,
                    'to' => $payload['report_result'] ?? $payload['generic_report'] ?? null,
                ],
            ],
            'Custom task finalized through visit validation.'
        );

        // nothing else to update (no master table)
    }


    public function leadDropView(mission $task)
    {
        $prospect = null;
       $prospect = \App\Models\Prospect::with([
        'hospital.province',
        'department',
        'unit',
        'config',
        'temperature',
        'review',
        ])->findOrFail($task->code_ref);

        return view('admin.lead_action_drop', compact('task', 'prospect'));
    }
    public function leadDropSubmit(Request $request, mission $task)
    {
        $request->validate([
            'report_result' => ['required', 'string'],
            'drop_comment' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $existing = MissionValidationList::where('mission_id', $task->id)
                ->where('status', 0)
                ->exists();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This task already has pending validation.');
            }

            $payload = [
                'action_type' => 'drop',
                'drop_comment' => $request->drop_comment,
                'report_result' => $request->report_result,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];

            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->report_result = $request->report_result;
            $task->status_mission = 6; // waiting validation
            $task->updated_by = auth()->id();
            $task->save();

            DB::commit();

            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Lead drop request submitted for validation.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function leadPromoView(mission $task)
    {
         $prospect = \App\Models\Prospect::with([
            'hospital.province',
            'department',
            'unit',
            'config',
            'temperature',
            'review',
        ])->findOrFail($task->code_ref);

        return view('admin.lead_action_promo', compact('task', 'prospect'));
    }

    public function leadPromoSubmit(Request $request, mission $task)
    {
        $request->validate([
            'promo_comment' => ['required', 'string'],
            'report_result' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $existing = MissionValidationList::where('mission_id', $task->id)
                ->where('status', 0)
                ->exists();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This task already has pending validation.');
            }

            $payload = [
                'action_type' => 'promo',
                'promo_comment' => $request->promo_comment,
                'report_result' => $request->report_result,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];

            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->report_result = $request->report_result;
            $task->status_mission = 6;
            $task->updated_by = auth()->id();
            $task->save();

            DB::commit();

            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Lead promo request submitted for validation.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    public function leadProspectView(mission $task)
    {
        $prospect = \App\Models\Prospect::with([
            'hospital.province',
            'department',
            'unit',
            'config',
            'temperature',
            'review',
        ])->findOrFail($task->code_ref);

        return view('admin.lead_action_prospect', compact('task', 'prospect'));
    }

    public function leadProspectSubmit(Request $request, mission $task)
    {
        $request->validate([
            'eta_po_date' => ['required', 'date'],
          'first_offer_date' => ['nullable', 'date'],
             'demo_date' => ['nullable', 'date'],
           'presentation_date' => ['nullable', 'date'],
            'unit_id' => ['required', 'integer'],

            'config_id' => ['required', 'integer'],

            'eta_po_date' => ['nullable', 'date'],
            'user_status' => ['nullable', 'string'],
            'direksi_status' => ['nullable', 'string'],
            'purchasing_status' => ['nullable', 'string'],
            'anggaran_status' => ['required', 'string'],
            'jenis_anggaran' => ['required', 'string'],
            'chance' => ['nullable'],
            'comment' => ['nullable', 'string'],

            'report_result' => ['required', 'string'],

            'unit_id' => ['nullable', 'integer'],
            'category_id' => ['nullable', 'integer'],
            'config_id' => ['nullable', 'integer'],
        ]);




        //  dd('validated', $request->all());
        DB::beginTransaction();

        try {

            $payload = [
                'action_type' => 'lead_to_prospect',

                'unit_id' => $request->unit_id,
                'config_id' => $request->config_id,

                // 'first_offer_date' => $request->first_offer_date,
                // 'demo_date' => $request->demo_date,
                // 'presentation_date' => $request->presentation_date,

                'user_status' => $request->user_status,
                'direksi_status' => $request->direksi_status,
                'purchasing_status' => $request->purchasing_status,
                'anggaran_status' => $request->anggaran_status,
                'jenis_anggaran' => $request->jenis_anggaran,
                'chance' => $request->chance,
                'comment' => $request->comment,
                'next_action' => $request->next_action,
                'report_result' => $request->report_result,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];


            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->status_mission = 6;
            $task->report_result = $request->report_result;
            $task->save();

            DB::commit();

            return redirect()->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Lead converted to prospect (waiting validation)');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    public function leadDelayedView(mission $task)
    {
        $prospect = null;
        if ($task->code_ref) {
            $prospect = \App\Models\Prospect::with('temperature')-> find($task->code_ref);
             $stage = $this->getProspectStage($prospect);
        }


        return view('admin.lead_action_delayed', compact('task', 'prospect', 'stage'));
    }

    public function leadDelayedSubmit(Request $request, mission $task)
    {
        $request->validate([
            'delay_comment' => ['required', 'string'],
            'delay_until' => ['required', 'date'],
            'report_result' => ['required', 'string'],
        ]);

        DB::beginTransaction();

        try {
            $existing = MissionValidationList::where('mission_id', $task->id)
                ->where('status', 0)
                ->exists();

            if ($existing) {
                return redirect()->back()->withInput()->with('error', 'This task already has pending validation.');
            }

            $payload = [
                'action_type' => 'delayed',
                'delay_comment' => $request->delay_comment,
                'delay_until' => $request->delay_until,
                'report_result' => $request->report_result,
                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];

            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->report_result = $request->report_result;
            $task->status_mission = 6; // waiting validation
            $task->updated_by = auth()->id();
            $task->save();

            DB::commit();

            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Delayed lead request submitted for validation.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }


    private function getProspectStage(?\App\Models\Prospect $prospect): array
    {
        $code = optional($prospect->temperature)->tempCodeName;
        $name = optional($prospect->temperature)->tempName;

        return [
            'code' => $code,
            'name' => $name,
            'is_lead' => (int)$code === 1,
            'is_promo' => (int)$code === 6,
            'is_delayed' => (int)$code === 7,
            'is_prospect' => in_array((int)$code, [2,3,4,5]),
            'is_drop' => (int)$code === 0,
            'is_missed' => (int)$code === -1,
        ];
    }

    private function prospectDropUpdate($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with(['review', 'temperature'])->findOrFail($validation->code_ref);

        $review = $prospect->review;
        if (!$review) {
            $review = \App\Models\Review::create([
                'prospect_id' => $prospect->id,
            ]);
        }

        // update review comment with drop + validator comment
        $baseComment = trim((string) ($review->comment ?? ''));
        $dropComment = '[Drop Reason] ' . ($payload['drop_comment'] ?? '');
        $validatorComment = !empty($payload['validator_comment'])
            ? "\n[Validator] " . $payload['validator_comment']
            : '';

        $newComment = trim($baseComment . "\n" . $dropComment . $validatorComment);

        if ($baseComment !== $newComment) {
            \App\Models\ReviewLog::create([
                'review_id' => $review->id,
                'log_date' => now()->toDateString(),
                'col_update' => 'comment',
                'col_before' => $review->comment,
                'col_after' => $newComment,
                'updated_by' => auth()->id(),
            ]);
            $review->comment = $newComment;
            $review->save();
        }
        // update temperature to Drop
        $temperature = $prospect->temperature;
        if ($temperature) {
            $temperature->tempName = 'Drop';
            $temperature->tempCodeName = 0;
            $temperature->save();
        }
    }


    private function prospectDelayedUpdate($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with(['review', 'temperature'])->findOrFail($validation->code_ref);

        $review = $prospect->review;
        if (!$review) {
            $review = \App\Models\Review::create([
                'prospect_id' => $prospect->id,
            ]);
        }

        $baseComment = trim((string) ($review->comment ?? ''));
        $delayComment = '[Delayed Reason] ' . ($payload['delay_comment'] ?? '');
        $delayUntil = !empty($payload['delay_until'])
            ? "\n[Delay Until] " . $payload['delay_until']
            : '';
        $validatorComment = !empty($payload['validator_comment'])
            ? "\n[Validator] " . $payload['validator_comment']
            : '';

        $newComment = trim($baseComment . "\n" . $delayComment . $delayUntil . $validatorComment);

        if ($baseComment !== $newComment) {
            \App\Models\ReviewLog::create([
                'review_id' => $review->id,
                'log_date' => now()->toDateString(),
                'col_update' => 'comment',
                'col_before' => $review->comment,
                'col_after' => $newComment,
                'updated_by' => auth()->id(),
            ]);

            $review->comment = $newComment;
            $review->save();
        }

        $temperature = $prospect->temperature;
        if ($temperature) {
            $temperature->tempName = 'Delayed Lead';
            $temperature->tempCodeName = 7;
            $temperature->save();
        }
    }


    private function createDelayedProspectTask($task, array $payload): void
    {
        $delayUntil = $payload['delay_until'] ?? null;
        $delayComment = trim((string)($payload['delay_comment'] ?? ''));

        if (empty($delayUntil)) {
            return;
        }

        $purpose = 'Follow up delayed lead';
        if ($delayComment !== '') {
            $purpose .= ' - ' . $delayComment;
        }

        $newTask = new mission();
        $newTask->code = mission::makeCode('prospect');
        $newTask->hospital_id = $task->hospital_id;
        $newTask->department = $task->department;
        $newTask->code_ref = $task->code_ref;
        $newTask->task_reference = 'prospect';
        $newTask->task_purpose = $purpose;
        $newTask->task_creator_id = auth()->id();
        $newTask->generate_task_via = 'delayed_lead';
        $newTask->deadline = $delayUntil;
        $newTask->priority_level = $task->priority_level ?? 'Urgent';
        $newTask->expected_outcome = 'Recheck delayed lead and continue follow up';
        $newTask->report_result = null;
        $newTask->status_mission = 0;
        $newTask->updated_by = null;
        $newTask->mission_run_id = null;
        $newTask->pic_user_id = null;
        $newTask->user_to_meet = $task->user_to_meet;
        $newTask->save();
    }


    private function prospectPromoUpdate($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with(['review', 'temperature'])->findOrFail($validation->code_ref);

        $review = $prospect->review;
        if (!$review) {
            $review = \App\Models\Review::create([
                'prospect_id' => $prospect->id,
            ]);
        }

        $baseComment = trim((string) ($review->comment ?? ''));
        $promoComment = '[Promo Reason] ' . ($payload['promo_comment'] ?? '');
        $validatorComment = !empty($payload['validator_comment'])
            ? "\n[Validator] " . $payload['validator_comment']
            : '';

        $newComment = trim($baseComment . "\n" . $promoComment . $validatorComment);

        if ($baseComment !== $newComment) {
            \App\Models\ReviewLog::create([
                'review_id' => $review->id,
                'log_date' => now()->toDateString(),
                'col_update' => 'comment',
                'col_before' => $review->comment,
                'col_after' => $newComment,
                'updated_by' => auth()->id(),
            ]);

            $review->comment = $newComment;
            $review->save();
        }
        // assign PIC from task PIC
        $prospect->pic_user_id = $task->pic_user_id;
        // forced promo defaults
        $prospect->unit_id = 10;      // Need To Follow UP
          // General
        $prospect->config_id = 0;     // General Product
        // generate promo number if empty
        if (empty($prospect->promo_no)) {
            $rand = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
            $promo_no = $this->generatePromoNo(). '-' . $prospect->province_id . '-' . $prospect->hospital_id . '-' . $rand;
            $prospect->promo_no = $promo_no;
        }

        $prospect->save();

        $temperature = $prospect->temperature;
        if ($temperature) {
            $temperature->tempName = 'Promo';
            $temperature->tempCodeName = 6;
            $temperature->save();
        }
    }

    private function createPromoProspectTask($task, array $payload): void
    {
        $newTask = new mission();
        $newTask->code = mission::makeCode('prospect');
        $newTask->hospital_id = $task->hospital_id;
        $newTask->department = $task->department;
        $newTask->code_ref = $task->code_ref;
        $newTask->task_reference = 'prospect';
        $newTask->task_purpose = 'Follow up promo prospect';
        $newTask->task_creator_id = auth()->id();
        $newTask->generate_task_via = 'promo_from_lead';
        $newTask->deadline = now()->addWeeks(2)->toDateString();
        $newTask->priority_level = $task->priority_level ?? 'Urgent';
        $newTask->expected_outcome = 'Update promo prospect and prepare escalation to prospect';
        $newTask->report_result = null;
        $newTask->status_mission = 0;
        $newTask->updated_by = null;
        $newTask->mission_run_id = null;
        $newTask->pic_user_id = $task->pic_user_id; // same PIC as task
        $newTask->user_to_meet = $task->user_to_meet;
        $newTask->save();
    }

    private function generatePromoNo(): string
    {
        $prefix = 'ISSPRM';
        $year = now()->format('y');


        return $prefix . '-' . $year;
    }

    public function promoProspectView(mission $task)
    {
        $prospect = \App\Models\Prospect::with([
            'hospital.province',
            'department',
            'unit',
            'config',
            'temperature',
            'review',
        ])->findOrFail($task->code_ref);

        $businessUnits = \App\Models\Unit::orderBy('name')->get();
        $categories = \App\Models\Category::orderBy('name')->get();

        return view('admin.promo_to_prospect', compact(
            'task',
            'prospect',
            'businessUnits',
            'categories'
        ));
    }

    public function promoToProspectSubmit(Request $request, mission $task)
    {
        $request->validate([
            'first_offer_date' => ['nullable', 'date'],
            'demo_date' => ['nullable', 'date'],
            'presentation_date' => ['nullable', 'date'],
            'last_offer_date' => ['nullable', 'date'],
            'user_status' => ['nullable', 'string'],
            'direksi_status' => ['nullable', 'string'],
            'purchasing_status' => ['nullable', 'string'],
            'anggaran_status' => ['required', 'string'],
            'jenis_anggaran' => ['required', 'string'],
            'chance' => ['required', 'numeric'],
            'comment' => ['nullable', 'string'],
            'next_action' => ['required', 'string'],
            'report_result' => ['required', 'string'],
        ]);

        dd('validated', $request->all());
        DB::beginTransaction();

        try {
            $payload = [
                'action_type' => 'promo_to_prospect',

                'first_offer_date' => $request->first_offer_date,
                'demo_date' => $request->demo_date,
                'presentation_date' => $request->presentation_date,
                'last_offer_date' => $request->last_offer_date,
                'user_status' => $request->user_status,
                'direksi_status' => $request->direksi_status,
                'purchasing_status' => $request->purchasing_status,
                'anggaran_status' => $request->anggaran_status,
                'jenis_anggaran' => $request->jenis_anggaran,
                'chance' => $request->chance,
                'comment' => $request->comment,
                'next_action' => $request->next_action,

                'report_result' => $request->report_result,

                'submitted_by' => auth()->id(),
                'submitted_at' => now()->toDateTimeString(),
            ];

            MissionValidationList::create([
                'mission_id' => $task->id,
                'task_ref' => $task->task_reference,
                'code_ref' => $task->code_ref,
                'payload_form' => json_encode($payload),
                'status' => 0,
            ]);

            $task->report_result = $request->report_result;
            $task->status_mission = 6;
            $task->updated_by = auth()->id();
            $task->save();

            DB::commit();

            return redirect()
                ->route('missions.runs.show', $task->mission_run_id)
                ->with('success', 'Promo converted to prospect request submitted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    private function prospectFinalConvert($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with(['review', 'temperature'])
            ->findOrFail($validation->code_ref);

        // generate prospect number
        if (empty($prospect->prospect_no)) {
            $provinceCode = optional(optional($prospect->hospital)->province)->code ?? '00';
            $prospect->prospect_no = $this->generateProspectNo($provinceCode, auth()->user()->role);
        }


        $prospect->pic_user_id = $task->pic_user_id?? $prospect->pic_user_id;
        $prospect->unit_id = $payload['unit_id'] ?? $prospect->unit_id;
        $prospect->config_id = $payload['config_id'] ?? $prospect->config_id;
        $prospect->eta_po_date = $payload['eta_po_date'] ?? $prospect->eta_po_date;
        $prospect->user_status = $payload['user_status'] ?? $prospect->user_status;
        $prospect->direksi_status = $payload['direksi_status'] ?? $prospect->direksi_status;
        $prospect->purchasing_status = $payload['purchasing_status'] ?? $prospect->purchasing_status;
        $prospect->anggaran_status = $payload['anggaran_status'] ?? $prospect->anggaran_status;
        $prospect->jenis_anggaran = $payload['jenis_anggaran'] ?? $prospect->jenis_anggaran;
        $prospect->chance = $payload['chance'] ?? $prospect->chance;
        $prospect->save();

        $prospect->save();

        // update temperature → Prospect
        $temperature = $prospect->temperature;
        if ($temperature) {
            $temperature->tempName = 'Prospect';
            $temperature->tempCodeName = 2;
            $temperature->save();
        }

        // update review fields (reuse your function)
        $this->prospectDataUpdate($validation, $task, $payload);
    }

    private function generateProspectNo($provinceCode = null, $role = null): string
    {
        $date = now();

        $codedate = $date->format('ymd');

        // province logic
        if ($role !== 'prj') {
            $prov = $provinceCode ?? '00';
        } else {
            $prov = '88';
        }

        // safer unique suffix
        $rand = str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);

        return "ISSP-{$prov}-{$codedate}-{$rand}";
    }

    private function prospectLeadConvert($validation, $task, array $payload): void
    {
        $prospect = \App\Models\Prospect::with(['review', 'temperature'])
            ->findOrFail($validation->code_ref);
        // generate prospect no
            $provinceCode = $prospect->province_id ?? '00';

        $prospect_no =$this->generateProspectNo($provinceCode, auth()->user()->role);

        $prospect->prospect_no = $prospect_no;
        $prospect->pic_user_id = $task->pic_user_id;
        $prospect->unit_id = $payload['unit_id'] ?? $prospect->unit_id;
        $prospect->config_id = $payload['config_id'] ?? $prospect->config_id;
        $prospect->eta_po_date = $payload['eta_po_date'] ?? $prospect->eta_po_date;
        $prospect->user_status = $payload['user_status'] ?? $prospect->user_status;
        $prospect->direksi_status = $payload['direksi_status'] ?? $prospect->direksi_status;
        $prospect->purchasing_status = $payload['purchasing_status'] ?? $prospect->purchasing_status;
        $prospect->anggaran_status = $payload['anggaran_status'] ?? $prospect->anggaran_status;
        $prospect->jenis_anggaran = $payload['jenis_anggaran'] ?? $prospect->jenis_anggaran;
        $prospect->chance = $payload['chance'] ?? $prospect->chance;
        $prospect->save();

        // update stage → Prospect
        $temperature = $prospect->temperature;
        if ($temperature) {
            $temperature->tempName = 'Prospect';
            $temperature->tempCodeName = 2;
            $temperature->save();
        }

        // update review data
        $this->prospectDataUpdate($validation, $task, $payload);
    }



}
