<?php

namespace App\Services;

use App\Models\MissionRun;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VisitCalendarService
{
    public function build(?Request $request = null): array
    {
        $weekStartInput = $request?->get('week_start');

        $calendarStart = $weekStartInput
            ? Carbon::parse($weekStartInput)->startOfWeek(Carbon::MONDAY)
            : now()->startOfWeek(Carbon::MONDAY);

        $calendarEnd = $calendarStart->copy()->endOfWeek(Carbon::SUNDAY);

        $calendarHours = [
            '08:00','10:00','12:00','14:00',
            '16:00','18:00','20:00','22:00',
        ];

        // 👉 ONLY mission_run
        $visits = MissionRun::with(['hospital:id,name,city'])
            ->whereNotNull('schedule_date')
            ->whereNotNull('schedule_time')
            ->whereBetween('schedule_date', [
                $calendarStart->toDateString(),
                $calendarEnd->toDateString(),
            ])
            ->get();

        $calendarVisits = [];

        foreach ($visits as $visit) {

            $dateKey = Carbon::parse($visit->schedule_date)->toDateString();
            $startTime = substr($visit->schedule_time, 0, 5);
            $duration = (int) ($visit->schedule_duration_minutes ?? 120);

            $blocks = max(1, ceil($duration / 120));
            $startIndex = array_search($startTime, $calendarHours, true);

            if ($startIndex === false) continue;

            for ($i = 0; $i < $blocks; $i++) {

                $slotIndex = $startIndex + $i;

                if (!isset($calendarHours[$slotIndex])) break;

                $slotTime = $calendarHours[$slotIndex];

                $calendarVisits[$dateKey][$slotTime][] = [
                    'id' => $visit->id,
                    'code' => $visit->code,
                    'hospital' => $visit->hospital?->name ?? '-',
                    'city' => $visit->hospital?->city ?? '',
                    'pic' => $visit->person_in_charge,
                    'status' => $visit->status,
                    'is_start' => $i === 0,
                    'rowspan' => $i === 0 ? $blocks : 0,
                ];
            }
        }

        return [
            'calendarStart' => $calendarStart,
            'calendarHours' => $calendarHours,
            'calendarVisits' => $calendarVisits,
        ];
    }
}
