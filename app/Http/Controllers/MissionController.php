<?php

namespace App\Http\Controllers;

use App\Models\mission;
use App\Models\Hospital;
use App\Models\Province;
use App\Models\User;
use App\Models\installbase;
use App\Models\Product;
use App\Models\MissionHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class MissionController extends Controller
{
    //

    public function autoFromInstallbase(Request $request)
    {
        $request->validate([
            'installbase_id' => ['required', 'integer'],
            'hospital_id' => ['required', 'integer'],
            'department' => ['nullable', 'string', 'max:255'],
            'missing_columns' => ['nullable', 'array'],
            'missing_columns.*' => ['string'],
        ]);

        // optional safety check: installbase must exist
        $ib = installbase::query()
            ->select('id', 'installbase_code', 'hospital_id', 'department')
            ->where('id', $request->installbase_id)
            ->firstOrFail();

        // (optional) ensure hospital_id matches installbase hospital_id (avoid wrong payload)
        if ((int) $ib->hospital_id !== (int) $request->hospital_id) {
            return response()->json([
                'ok' => false,
                'message' => 'Hospital mismatch with installbase data.',
            ], 422);
        }

        $missing = $request->missing_columns ?? [];
        $missingStr = count($missing) ? implode(', ', $missing) : '-';

        $expectedOutcome = "Filled missing column: {$missingStr}";

        // Generate code in controller (your habit)
        $code = mission::makeCode('installbase');

        // Optional: prevent duplicate open missions for same IB
        // $exists = mission::query()
        //     ->where('task_reference', 'installbase')
        //     ->where('code_ref', (string) $request->installbase_id)
        //     ->whereIn('status_mission', [0, 1, 2]) // active states
        //     ->exists();

        // if ($exists) {
        //     return response()->json([
        //         'ok' => false,
        //         'message' => 'Mission already exists for this installbase (still active).',
        //     ], 409);
        // }

        $mission = mission::create([
            'code' => $code,
            'hospital_id' => $request->hospital_id,
            'department' => $request->department, // keep empty if empty
            'pic_user_id' => null,               // set later
            'user_to_meet' => null,              // optional later
            'code_ref' => (string) $request->installbase_id,
            'task_reference' => 'installbase',
            'task_purpose' => 'review to update installbase data',
            'task_creator_id' => auth()->id(),
            'generate_task_via' => 'installbase_menu',
            'deadline' => Carbon::today()->addWeeks(2)->toDateString(),
            'priority_level' => 'Urgent',
            'expected_outcome' => $expectedOutcome,
            'report_result' => null,
            'status_mission' => 0, // tasklist
            'updated_by' => null,
        ]);

        return response()->json([
            'ok' => true,
            'message' => "Mission created: {$mission->code}",
            'mission_id' => $mission->id,
            'code' => $mission->code,
        ]);
    }



    public function taskPool(Request $request)
    {
        $provinceId = (int) $request->get('province_id', 0);
        $hospitalId = (int) $request->get('hospital_id', 0);

        // Filter options
        $provinces = Province::query()
        ->whereIn('id', function ($q) {
            $q->select('h.province_id')
              ->from('missions as m')
              ->join('hospitals as h', 'h.id', '=', 'm.hospital_id')
              ->whereIn('m.status_mission', [0,30])
              ->whereNotNull('h.province_id')
              ->distinct();
        })
        ->orderBy('name')
        ->get(['id','name']);
        $hospitals = Hospital::query()
            ->when($provinceId, fn($q) => $q->where('province_id', $provinceId))
            ->orderBy('name')
            ->get(['id','name','province_id']);

        // Task pool data (status 0)
        $missions = mission::query()
            ->with(['hospital:id,name,city,province_id'])
            ->whereIn('status_mission', [0,30]) // include requested tasks (30) that still in pool
             ->whereNotNull('hospital_id'); // only missions with hospital (filter out manual/custom tasks without hospital)
            if ($hospitalId) {
                $missions->where('hospital_id', $hospitalId);
            } elseif ($provinceId) {
                // if only province picked, show all hospitals inside that province
                $missions->whereIn('hospital_id', function ($q) use ($provinceId) {
                    $q->select('id')->from('hospitals')->where('province_id', $provinceId);
                });
            }

            $missions = $missions->orderByDesc('created_at')->get();

        // group by task_reference (installbase, prospect, mapping, custom, etc.)
        $grouped = $missions->groupBy('task_reference');

        // PIC options (AM/NSM will pick later)
        $pics = User::orderBy('name')->get(['id','name']);

        return view('admin.task_pool', compact(
            'grouped','provinces','hospitals','pics','provinceId','hospitalId'
        ));
    }

    public function taskPoolHospitalsByProvince($provinceId)
    {
        // Hospitals in that province that exist in TASK POOL (status 0)
        $hospitals = Hospital::query()
            ->where('province_id', $provinceId)
            ->whereIn('id', function ($q) {
                $q->select('hospital_id')
                ->from('missions')
                ->whereIn('status_mission', [0,30])
                ->whereNotNull('hospital_id')
                ->distinct();
            })
            ->orderBy('name')
            ->get(['id','name']);

        return response()->json($hospitals);
    }


    public function bulkToMission(Request $request)
    {
        $request->validate([
            'mission_ids' => ['required','array','min:1'],
            'mission_ids.*' => ['integer','exists:missions,id'],
            'pic_user_id' => ['nullable','integer','exists:users,id'],
            'deadline' => ['required','date'],
        ]);
        $ids = $request->mission_ids;

        $missions = mission::whereIn('id', $ids)
        ->whereIn('status_mission', [0,30]) // only from task pool or requested (still in pool)
        ->get();

        foreach ($missions as $m) {
        $before = [
            'status_mission' => $m->status_mission,
            'pic_user_id' => $m->pic_user_id,
            'deadline' => $m->deadline,
        ];

        $m->update([
            'status_mission' => 1,
            'pic_user_id' => $request->pic_user_id,
            'deadline' => $request->deadline,
            'updated_by' => auth()->id(),
        ]);

        $this->logMissionChange($m->id, 'moved_to_mission_pool', [
            'status_mission' => ['from' => $before['status_mission'], 'to' => 1],
            'pic_user_id' => ['from' => $before['pic_user_id'], 'to' => $m->pic_user_id],
            'deadline' => ['from' => $before['deadline'], 'to' => $m->deadline],
        ]);
        }

        return response()->json([
            'message' => 'Missions created and moved to Mission Pool.','count' => $missions->count(),
        ]);
    }


    //log history helper

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




    public function pendingMission($hospitalId)
    {
        $hospital = Hospital::findOrFail($hospitalId);

        // Dummy Data (simulate automation result)
        $missions = [
            [
                'equipment_id' => 'IB000552',
                'issue' => 'Warranty Expired',
                'priority' => 'High',
                'deadline' => '2025-02-01'
            ],
            [
                'equipment_id' => 'IB000583',
                'issue' => 'Department Missing',
                'priority' => 'Medium',
                'deadline' => '2025-02-10'
            ],
            [
                'equipment_id' => 'IB000622',
                'issue' => 'No Contract Coverage',
                'priority' => 'High',
                'deadline' => '2025-01-28'
            ],
            [
                'equipment_id' => 'IB000711',
                'issue' => 'Last Service > 12 Months',
                'priority' => 'Medium',
                'deadline' => '2025-02-05'
            ],
            [
                'equipment_id' => 'IB000801',
                'issue' => 'Condition Not Updated',
                'priority' => 'Low',
                'deadline' => '2025-02-15'
            ],
            [
                'equipment_id' => 'IB000899',
                'issue' => 'Contract Ending Soon',
                'priority' => 'High',
                'deadline' => '2025-01-30'
            ],
        ];

    return view('admin.hospitalpendingmission', compact('hospital','missions'));
    }


    public function missionPool(Request $request)
    {
        $weekStart = $request->get('week_start')
            ? Carbon::parse($request->week_start)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = (clone $weekStart)->addDays(6)->endOfDay();

        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $d = (clone $weekStart)->addDays($i);
            $days[] = [
                'date' => $d->toDateString(),         // "2026-03-02"
                'label_date' => $d->format('d-M-y'),  // "02-Mar-26"
                'label_day' => strtoupper($d->format('D')), // "MON"
            ];
        }

        // Mission list (status 1 & 2) untuk tabel kanan
        $missions = mission::with(['hospital:id,name,city', 'picUser:id,name'])
                ->whereIn('status_mission', [1, 2])
                ->orderByDesc('updated_at')
                ->limit(200)
                ->get();

        // status 2 = scheduled (based on your flow)
        $scheduled = mission::with(['hospital:id,name,city'])
            ->where('status_mission', 2)
            ->whereBetween('schedule_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->whereNotNull('schedule_time')
            ->get();

        // group by date + HH:MM (strip seconds)
        $grid = [];
        foreach ($scheduled as $m) {
            $dateKey = Carbon::parse($m->schedule_date)->toDateString();
            $timeKey = $m->schedule_time ? substr($m->schedule_time, 0, 5) : null; // "08:00"
            if (!$dateKey || !$timeKey) continue;

            $grid[$timeKey][$dateKey][] = [
                'id' => $m->id,
                'code' => $m->code,
                'hospital' => $m->hospital?->name,
                'city' => $m->hospital?->city,
                'pic' => $m->picUser?->name ?? '-',
                'department' => $m->department,
            ];
        }

    $hospitalCount = mission::whereIn('status_mission', [1,2])->whereNotNull('hospital_id')->distinct('hospital_id')->count('hospital_id');
    $missionCount  = mission::whereIn('status_mission', [1,2])->count();

    return view('admin.mission_pool', compact(
        'weekStart',
        'weekEnd',
        'days',
        'times',
        'grid',
        'missions',
        'hospitalCount',
        'missionCount'
    ));


    }
}
