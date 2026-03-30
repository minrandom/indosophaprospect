<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use App\Models\MissionHistory;
use App\Models\MissionRun;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Services\VisitCalendarService;

class MissionPoolController extends Controller
{
    public function index(Request $request)
    {
        $visitCalendarService = new VisitCalendarService();
        $calendar = $visitCalendarService->build($request);
        //dd($calendar);

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
         $runs = MissionRun::query()
        ->with([
            'hospital:id,name,city',
            'picUser:id,name',
            'creator:id,name', // kalau ada
        ])
        // task yang sudah ditambahkan ke mission run (status 1 = mission pool)
        ->withCount([
            'tasks as tasks_count' => function ($q) {
                $q->whereIn('status_mission', [1,2,3]);
            }
        ])
        // optional filter: hanya run yg masih aktif (sesuaikan field status milik mission_run kamu)
        // ->whereIn('status', [0,1,2])
        ->orderByDesc('id')
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

        $hospitalCount = Mission::whereIn('status_mission', [1,2])->whereNotNull('hospital_id')->distinct('hospital_id')->count('hospital_id');
        $missionCount  = Mission::whereIn('status_mission', [1,2])->count();

        return view('admin.mission_pool', compact(
            'weekStart',
            'weekEnd',
            'days',
            // 'times',
            'grid',
            'runs',
            'hospitalCount',
            'missionCount',
            'calendar'
        ));
    }

    private function logMissionChange(int $missionId, string $action, array $changes = [], ?string $note = null): void
    {
        MissionHistory::create([
            'mission_id' => $missionId,
            'actor_user_id' => auth()->id(),
            'action' => $action,
            'changes' => $changes ?: null, // cast handles array->json
            'note' => $note,
        ]);
    }

    public function saveSchedule(Request $request)
    {
        $request->validate([
            'mission_id'    => 'required|integer|exists:missions,id',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required|date_format:H:i',
            'duration'      => 'required|integer|in:30,60,90',
        ]);

        // Force 30-min slot
        $mm = (int) substr($request->schedule_time, 3, 2);
        if (!in_array($mm, [0, 30], true)) {
            return response()->json(['message' => 'Schedule time must be 30-minute slot.'], 422);
        }

        $m = Mission::findOrFail($request->mission_id);

        // ✅ PIC required
        if (empty($m->pic_user_id)) {
            return response()->json([
                'message' => 'PIC is required. Assign PIC first before scheduling.'
            ], 422);
        }

        $m->schedule_date = $request->schedule_date;
        $m->schedule_time = $request->schedule_time . ':00';
        $m->schedule_duration_minutes = (int) $request->duration;
        $m->status_mission = 2; // scheduled
        $m->updated_by = auth()->id();
        $m->save();

        return response()->json(['message' => 'Scheduled successfully.']);
    }



    public function start(Mission $mission)
    {
        // must be scheduled (2) before start
        if ((int)$mission->status_mission !== 2) {
            return response()->json(['message' => 'Mission must be in scheduled status first.'], 422);
        }
        if (!$mission->schedule_date || !$mission->schedule_time) {
            return response()->json(['message' => 'Schedule date/time is required.'], 422);
        }

        $before = $mission->only(['status_mission']);

        $mission->status_mission = 3; // on progress
        $mission->updated_by = auth()->id();
        $mission->save();

        $this->logMissionChange($mission->id, 'started', [
            'status_mission' => ['from' => $before['status_mission'], 'to' => 3],
        ]);

        return response()->json(['message' => 'Mission started.']);
    }



    public function detail(Mission $mission)
    {
        $mission->load([
            'hospital:id,name,city',
            'picUser:id,name',
            'histories.actor:id,name',
            'report:id,mission_id,summary,reporter_user_id,created_at',
            'report.reporter:id,name',
        ]);

        return response()->json([
            'mission' => [
                'id' => $mission->id,
                'code' => $mission->code,
                'task_reference' => $mission->task_reference,
                'task_purpose' => $mission->task_purpose,
                'priority_level' => $mission->priority_level,
                'expected_outcome' => $mission->expected_outcome,
                'deadline' => $mission->deadline,
                'hospital' => $mission->hospital?->name,
                'pic' => $mission->picUser?->name,
                'schedule' => ($mission->schedule_date && $mission->schedule_time)
                    ? ($mission->schedule_date.' '.$mission->schedule_time)
                    : null,
                'status_mission' => $mission->status_mission,
            ],
            'report' => $mission->report ? [
                'summary' => $mission->report->summary,
                'reporter' => $mission->report->reporter?->name,
                'created_at' => optional($mission->report->created_at)->format('d-M-y H:i'),
            ] : null,
            'histories' => $mission->histories->map(fn($h) => [
                'time' => optional($h->created_at)->format('d-M-y H:i'),
                'action' => $h->action,
                'actor' => $h->actor?->name ?? '-',
                'note' => $h->note,
            ])->values(),
        ]);
    }




}
