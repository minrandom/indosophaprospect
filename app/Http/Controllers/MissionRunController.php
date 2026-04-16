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

  public function tasks($id)
    {
        $run = MissionRun::with([
            'hospital:id,name,city',
            'tasks' => function ($q) {
                $q->with(['hospital:id,name,city'])
                ->whereIn('status_mission', [1,2,3]) // mission pool tasks
                ->orderBy('created_at', 'desc');
            }
        ])->findOrFail($id);

        return view('tabs._mission_run_tasks_table', [
            'run' => $run,
            'tasks' => $run->tasks,
        ]);
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

    public function show(Request $request, MissionRun $run)
    {
         $validationMode = (int) $request->get('validation_mode', 0);
        $run->load(['checkIn', 'checkOut']);

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
        $inMission = mission::with(['hospital:id,name,city'])
            ->where('mission_run_id', $run->id)
            ->whereIn('status_mission', [1,2,3,4,5,6,7])
            ->get()
            ->groupBy('task_reference');

        // right side: tasks in task pool (status 0) same hospital
        $taskPool = mission::with(['hospital:id,name,city'])
            ->whereNull('mission_run_id')
            ->where('hospital_id', $run->hospital_id)
            ->whereIn('status_mission', [0,30])
            ->get()
            ->groupBy('task_reference');

        $allVisitTasks = mission::where('mission_run_id', $run->id)->get();
        $totalTaskCount = $allVisitTasks->count();
        $validatedTaskCount = $allVisitTasks->where('status_mission', 7)->count();





        return view('admin.mission_run_show', compact('run', 'inMission', 'taskPool', 'checkInPhotoShow', 'totalTaskCount', 'validatedTaskCount', 'validationMode'));
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
                    'schedule_date' => $request->schedule_date,
                    'schedule_time' => $request->schedule_time,
                    'schedule_duration_minutes' => $request->schedule_duration_minutes,
                    'updated_at' => now(),
                ]);

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
                return redirect()->route('missions.task.prospect', $task->id);

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

        switch (strtolower($validation->task_ref)) {
            case 'installbase':
                $installbase = \App\Models\Installbase::with([
                    'hospital.province',
                    'product.brand',
                    'product.category',
                ])->find($validation->code_ref);
                return view('admin.validation_preview_installbase', compact('task', 'validation', 'payload', 'installbase'));

            case 'prospect':
                return view('admin.validation_preview_prospect', compact('task', 'validation', 'payload'));

            case 'mapping':
                return view('admin.validation_preview_mapping', compact('task', 'validation', 'payload'));

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
        //task complete logic for prospect in visit detail
        $prospect = null;
        if($task->code_ref){
            $prospect = Prospect::where('id', $task->code_ref)->first();
        }
        return view('admin.mission_task_prospect', compact('task', 'prospect'));
    }

    public function updateProspectTask(Request $request, mission $task)
    {

    }

    public function prospectDataUpdate($validation, $task, array $payload): void
    {

    }



    public function showInstallbaseTask(mission $task)
    {
        //task complete logic for installbase in visit detail
        $installbase = null;
        if($task->code_ref){
            $installbase = installbase::with(['hospital.province','product'])->where('id', $task->code_ref)->first();

            $department = Department::where('id', $installbase->department_id)->first();
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

    public function validateTask(mission $task)
    {

        $validation = MissionValidationList::where('mission_id', $task->id)
        ->where('status', 0)
        ->latest()
        ->firstOrFail();
        $run = MissionRun::findOrFail($task->mission_run_id);

         if ((int)$run->status !== 6) {
            return redirect()->back()->with('error', 'Visit must be submitted before task validation.');
        }

        if ((int)$validation->status !== 0) {
            return redirect()->back()->with('error', 'This task validation has already been processed.');
        }

        $validation->status = 1;
        $validation->validate_by = auth()->id();
        $validation->validate_at = now();
        $validation->save();

        $task->status_mission = 7;
        $task->updated_by = auth()->id();
        $task->save();

        return redirect()->back()->with('success', 'Task validated successfully.');
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
                                $this->prospectDataUpdate($validation, $task, $payload);
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
        $newTask->generate_task_via = 'missing_task';
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




}
