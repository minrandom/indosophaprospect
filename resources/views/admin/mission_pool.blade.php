@extends('layout.backend.app',[
  'title' => 'Schedule',
  'pageTitle' => '',
])

@section('content')
<div class="container-fluid">

  {{-- TOP HEADER STRIP --}}
  <div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
    <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
      <div class="row align-items-center">
        <div class="col-12 col-lg-6 d-flex align-items-center mb-3 mb-lg-0">
          <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
          <div class="text-white font-weight-bold" style="letter-spacing:1px; font-size:26px;">
            Schedule          </div>
        </div>

        <div class="col-12 col-lg-6 text-lg-right text-white">
          <div class="small text-white-50">Week</div>
          <div class="font-weight-bold">
            {{ $weekStart->format('d-M-y') }} - {{ $weekEnd->format('d-M-y') }}
          </div>

          <div class="mt-2">
            <a class="btn btn-sm btn-light"
               href="{{ route('missions.pool', ['week_start' => $weekStart->copy()->subWeek()->toDateString()]) }}">
              Prev
            </a>
            <a class="btn btn-sm btn-light"
               href="{{ route('missions.pool', ['week_start' => $weekStart->copy()->addWeek()->toDateString()]) }}">
              Next
            </a>
          </div>
        </div>

      </div>
    </div>
  </div>

  {{-- MIDDLE ROW --}}
  <div class="row">

    {{-- LEFT SUMMARY --}}
    <div class="col-12 col-lg-4 mb-4">
      <div class="card shadow border-0 h-100" style="border-radius: 1.25rem; background:#4E73DF;">
        <div class="card-body text-white">

          <div class="mb-3" style="font-size:18px;">
            <div>No. of Hospital with task: <b>{{ $hospitalCount }}</b></div>
            <div>No. of Missions: <b>{{ $missionCount }}</b></div>
          </div>

          <div class="d-flex" style="gap:14px; flex-wrap:wrap;">
            <a href="{{ route('missions.taskPool') }}" class="text-white text-decoration-none">
              <div class="text-center" style="width:88px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                     style="width:64px;height:64px;background:#132A72;">
                  <i class="fas fa-home"></i>
                </div>
                <div class="small mt-1">Task List</div>
              </div>
            </a>

            <a href="{{ route('missions.pool') }}" class="text-white text-decoration-none">
              <div class="text-center" style="width:88px;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                     style="width:64px;height:64px;background:#132A72;">
                  <i class="fas fa-list"></i>
                </div>
                <div class="small mt-1">Visit List</div>
              </div>
            </a>

            <div class="text-center" style="width:88px;">
              <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto"
                   style="width:64px;height:64px;background:#132A72;">
                <i class="fas fa-check"></i>
              </div>
              <div class="small mt-1">Validate</div>
            </div>
          </div>

          <div class="mt-4 small text-white-50">
            * PIC is required before scheduling.
          </div>

        </div>
      </div>
    </div>

    {{-- RIGHT: MISSION RUN LIST --}}
<div class="col-12 col-lg-8 mb-4">
  <div class="card shadow border-0" style="border-radius: 1.25rem; background:#4E73DF;">
    <div class="card-body text-white">

      <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="h5 mb-0">Visit List</div>
      </div>

      <div class="table-responsive">
        <table class="table table-sm mb-0" style="color:#fff;">
          <thead style="background:#132A72;">
            <tr class="text-uppercase small">

              <th>Hospital</th>
              <th>Asigned Person</th>
              <th>Asigned By</th>
              <th class="text-center">Total Tasks</th>
              <th>Deadline</th>
              <th>Schedule</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>

            @forelse($runs as $run)
              <tr>
                {{-- <td class="font-weight-bold">
                  {{ $run->code ?? ('RUN-'.$run->id) }}
                </td> --}}

                <td>
                  {{ $run->hospital?->name ?? '-' }}
                  <div class="small text-white-50">
                    {{ $run->hospital?->city ?? '' }}
                  </div>
                </td>

                <td>
                  @if($run->picUser?->name)
                    {{ $run->picUser->name }}
                  @else
                    <span class="badge badge-danger">PIC Missing</span>
                  @endif
                </td>
                <td>
                  @if($run->creator?->name)
                    {{ $run->creator->name }}
                  @else
                    <span class="badge badge-danger">Creator Missing</span>
                  @endif
                </td>

                <td class="text-center">
                  <span class="badge badge-light">
                    {{ $run->tasks_count ?? 0 }}
                  </span>
                </td>

                <td>
                  {{ $run->deadline_mission ? \Carbon\Carbon::parse($run->deadline_mission)->format('d-M-y') : '-' }}
                </td>
                <td>
                  {{ $run->schedule_date ? \Carbon\Carbon::parse($run->schedule_date)->format('d-M-y') : '-' }}
                </td>

                {{-- // 0=draft, 1=idle, 2=scheduled, 3=on_progress, 4=cancel, 5=done, 6=under_review, -1=missed --}}
                <td>
                    @if($run->status_mission == 1)
                        <span class="badge badge-info"><i class="fa fa-clock"></i> Idle</span>
                    @elseif($run->status_mission == 2)
                        <span class="badge badge-secondary"><i class="fa fa-calendar"></i> Scheduled</span>
                    @elseif($run->status_mission == 3)
                        <span class="badge badge-warning"><i class="fa fa-running"></i> On Going</span>
                    @elseif($run->status_mission == 4)
                        <span class="badge badge-danger"><i class="fa fa-times"></i> Cancelled</span>
                    @elseif($run->status_mission == 5)
                        <span class="badge badge-success"><i class="fa fa-check"></i> Done</span>
                    @elseif($run->status_mission == 6)
                        <span class="badge badge-success"><i class="fa fa-file-signature"></i> Under Review</span>
                    @elseif($run->status_mission == 10)
                        <span class="badge badge-success"><i class="fa fa-flag-checkered"></i> Finished Review</span>
                    @elseif($run->status_mission == -1)
                        <span class="badge badge-dark"><i class="fa fa-times-circle"></i> Missed</span>
                    @else
                        <span class="badge badge-light">Unknown</span>
                    @endif
                </td>


                <td class="text-center">
                  {{-- nanti kamu isi: detail modal / schedule / start --}}
                  @if((int)$run->status === 2)
                    <button type="button"
                          class="btn btn-sm btn-light btn-start-run"
                          data-run-id="{{ $run->id }}"
                          style="border-radius:5px;"
                          data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                  <i class="fa fa-play" style="color:#132A72;"> Start</i>
                  </button>
                    @elseif((int)$run->status === 1 && $run->tasks_count > 0)
                    <button type="button"
                            class="btn btn-sm btn-light btn-schedule-mission"
                            data-run-id="{{ $run->id }}"
                            style="border-radius:5px;"
                            data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                    <i class="fa fa-calendar" style="color:#132A72;"> Schedule</i>
                    </button>
                    @elseif((int)$run->status === 3)
                    <a href="{{ route('missions.runs.show', $run->id) }}"
                    class="btn btn-sm btn-success"
                    style="border-radius:5px;">
                    Go Visiting
                    </a>
                    @elseif($run->tasks_count == 0)
                    <a href="{{  route('missions.taskPool')  }}" class="badge badge-secondary">No Task</a>

                    @endif
                    <button type="button"
                            class="btn btn-sm btn-light js-run-detail"
                            data-run-id="{{ $run->id }}"
                            style="border-radius:5px;"
                            data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                    <i class="fa fa-info-circle" style="color:#132A72;"> Detail</i>
                    </button>

                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center text-white-50">No Mission Runs</td>
              </tr>
            @endforelse

          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>


{{-- TASK LIST PANEL --}}
  <div class="col-12 mb-4" id="missionTasksPanel" style="display:none;">
<div class="card shadow border-0 mb-4" style="border-radius:1.25rem; height:420px;">
  <div class="card-body ">

    <div class="d-flex align-items-center justify-content-between mb-3">
      <div class="h6 font-weight-bold text-uppercase mb-0">
        Mission Tasks
      </div>

      <div class="small text-muted" id="runTasksTitle">
        Click <b>Detail</b> from the mission to show data...
      </div>
    </div>

    <div id="runTasksWrap" class="flex-fill overflow-auto text-center text-muted">
      <div class="py-5">
        Click <b>Detail</b> on the Mission List to load tasks here.
      </div>
    </div>
  </div>
  </div>
</div>

  {{-- SCHEDULE GRID --}}
   <div class="card shadow border-0 mb-4" style="border-radius: 1.25rem; background:#4E73DF;">
    <div class="card-body text-white">

      <div class="h5 mb-3">Schedule</div>
        @include('modal.reuseable._weekly_calendar', [
                'calendarModalId' => 'scheduleCalendarModal',
                'calendarTitle' => 'Schedule Weekly Calendar',

                'calendarStart' => $calendar['calendarStart'],
                'calendarHours' => $calendar['calendarHours'],
                'calendarVisits' => $calendar['calendarVisits'],
                'calendarId' => 'missionPoolCalendar'

            ])

    </div>

      {{-- <div class="mt-2 text-white-50 small">
        * Click any empty cell to schedule (PIC required).
      </div> --}}

    {{-- </div>--}}

</div>



@include('modal._mission_detail')
@include('modal._mission_schedul_modal')
@endsection

@push('js')

@include('modal.modalJS._mission_pool_js');
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script>
  window.missionPoolScheduleUrl = @json(route('missions.pool.schedule'));
  window.csrfToken = @json(csrf_token());
</script>

@endpush
