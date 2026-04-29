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

        $visitsQuery = MissionRun::with([
                'hospital:id,name,city,province_id',
                'hospital.province:id,name,prov_order_no,iss_area_code,wilayah',
                'picUser:id,name',
            ])
            ->whereNotNull('schedule_date')
            ->whereNotNull('schedule_time')
            ->whereBetween('schedule_date', [
                $calendarStart->toDateString(),
                $calendarEnd->toDateString(),
            ]);

        $visitsQuery = $this->applyVisitAreaScope($visitsQuery);

        $visits = $visitsQuery->get();

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
                    'pic' => $visit->picUser?->name ?? '-',
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

    private function applyVisitAreaScope($query)
    {
        $user = auth()->user();
        $role = strtolower($user->role ?? '');
        $area = optional($user->employee)->area;

        if ($area === 'HO' || in_array($role, ['admin', 'bu'])) {
            return $query;
        }

        if (!$area) {
            return $query->whereRaw('1 = 0');
        }

        if ($role === 'fs') {
            return $query
                ->where('person_in_charge', $user->id)
                ->whereHas('hospital.province', function ($q) use ($area) {
                    $q->where('prov_order_no', $area);
                });
        }

        return $query->whereHas('hospital.province', function ($q) use ($role, $area) {
            if ($role === 'am') {
                $q->where('iss_area_code', $area);
            } elseif ($role === 'nsm') {
                $q->where('wilayah', $area);
            } else {
                $q->whereRaw('1 = 0');
            }
        });
    }
}
