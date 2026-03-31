<?php

namespace App\Http\Controllers;

use App\Services\VisitCalendarService;
use App\Models\MissionRun;
use App\Models\mission;
use App\Models\Hospital;
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
            ->whereIn('status_mission', [1,2,3,4,5])
            ->get()
            ->groupBy('task_reference');

        // right side: tasks in task pool (status 0) same hospital
        $taskPool = mission::with(['hospital:id,name,city'])
            ->whereNull('mission_run_id')
            ->where('hospital_id', $run->hospital_id)
            ->whereIn('status_mission', [0,30])
            ->get()
            ->groupBy('task_reference');

        return view('admin.mission_run_show', compact('run', 'inMission', 'taskPool', 'checkInPhotoShow'));
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

    public function scheduleMissionRun(Request $request)
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



}
