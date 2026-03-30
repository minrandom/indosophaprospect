@php
    use Carbon\Carbon;

    $resolvedWeekStart = $calendarStart
        ?? request('week_start')
        ?? now()->toDateString();

    $calendarStart = Carbon::parse($resolvedWeekStart)->startOfWeek(Carbon::MONDAY);

    $calendarDays = [];
    for ($i = 0; $i < 7; $i++) {
        $d = $calendarStart->copy()->addDays($i);
        $calendarDays[] = [
            'date' => $d->toDateString(),
            'day' => strtoupper($d->format('D')),
            'label' => $d->format('d M'),
        ];
    }

    $calendarHours = [
        '08:00',
        '10:00',
        '12:00',
        '14:00',
        '16:00',
        '18:00',
        '20:00',
        '22:00',
    ];

    $calendarId = $calendarId ?? 'visitCalendarSection';

    $prevWeek = $calendarStart->copy()->subWeek()->toDateString();
    $nextWeek = $calendarStart->copy()->addWeek()->toDateString();

    $calendarVisits = $calendarVisits ?? [];
@endphp

<div id="{{ $calendarId }}" class="card shadow border-0 mb-4" style="border-radius:1.25rem;">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-3">
      <div class="h6 font-weight-bold text-uppercase mb-0">
        Visit Schedule Calendar
      </div>

      <div class="d-flex align-items-center" style="gap:8px;">
        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['week_start' => $prevWeek])) }}"
           class="btn btn-sm btn-secondary">
          Prev
        </a>

        <span class="small text-muted">
          Week of {{ $calendarStart->format('d M Y') }}
        </span>

        <a href="{{ url()->current() }}?{{ http_build_query(array_merge(request()->except('page'), ['week_start' => $nextWeek])) }}"
           class="btn btn-sm btn-secondary">
          Next
        </a>
      </div>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered table-sm mb-0 text-center">
        <thead class="thead-light">
          <tr>
            <th style="min-width:90px;">Time</th>
            @foreach($calendarDays as $day)
              <th style="min-width:140px;">
                <div class="font-weight-bold">{{ $day['day'] }}</div>
                <div class="small text-muted">{{ $day['label'] }}</div>
              </th>
            @endforeach
          </tr>
        </thead>

        <tbody>
            @php
                $skipMap = [];
            @endphp

            @foreach($calendarHours as $hourIndex => $hour)
                <tr>
                <th class="align-middle">{{ $hour }}</th>

                @foreach($calendarDays as $day)
                    @php
                    $date = $day['date'];

                    // skip covered cells
                    if (!empty($skipMap[$date][$hour])) {
                        continue;
                    }

                    $slotVisits = $calendarVisits[$date][$hour] ?? [];
                    @endphp

                    @if(!empty($slotVisits))
                    @php
                        $visit = $slotVisits[0]; // assume 1 visit per slot for now
                    @endphp

                    @if($visit['is_start'])
                        @php
                        $rowspan = max(1, (int)($visit['rowspan'] ?? 1));

                        // mark future slots as skipped
                        for ($r = 1; $r < $rowspan; $r++) {
                            $nextHour = $calendarHours[$hourIndex + $r] ?? null;
                            if ($nextHour) {
                                $skipMap[$date][$nextHour] = true;
                            }
                        }
                        @endphp

                        <td rowspan="{{ $rowspan }}"
                            class="align-middle calendar-slot"
                            data-date="{{ $date }}"
                            data-time="{{ $hour }}"
                            style="vertical-align:top;">

                        <div style="background:#4E73DF;color:#fff;border-radius:6px;padding:6px;text-align:left;height:100%;">
                            <div class="font-weight-bold">{{ $visit['code'] }}</div>
                            <div class="small">{{ $visit['hospital'] }}</div>
                            @if(!empty($visit['city']))
                            <div class="small">{{ $visit['city'] }}</div>
                            @endif
                        </div>

                        </td>
                    @endif
                    @else
                    <td class="align-middle calendar-slot"
                        data-date="{{ $date }}"
                        data-time="{{ $hour }}">
                    </td>
                    @endif
                @endforeach
                </tr>
            @endforeach
            </tbody>
      </table>
    </div>

  </div>
</div>
