@extends('layout.backend.app',[
  'title' => 'Schedule',
  'pageTitle' => '',
])

@section('content')
@php
    $role = strtolower(optional(auth()->user())->role ?? '');
    $canApproveVisit = in_array($role, ['admin', 'am', 'nsm']);

@endphp
<div class="container-fluid">
@include('layout.component.nav.navigation_button_task')
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

              <th class="text-center">Total Tasks</th>
              <th>Deadline</th>
              <th>Schedule</th>
              <th>Check In Status</th>
              <th>Status</th>
              <th class="text-center">Action</th>
            </tr>
          </thead>
          <tbody>

            @forelse($runs as $run)
            @php
              $isApproved = (int)$run->is_approve === 1;

                $isPic = (int)$run->person_in_charge === auth()->id();

            @endphp
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
                {{-- <td>
                  @if($run->creator?->name)
                    {{ $run->creator->name }}
                  @else
                    <span class="badge badge-danger">Creator Missing</span>
                  @endif
                </td> --}}

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

                <td>
                    @if($run->checkIn && $run->checkOut)
                        <span class="badge badge-success">Checked Out</span>
                        <div class="small ">
                            {{ $run->checkOut->created_at->format('d-M H:i') }}
                        </div>
                    @elseif($run->checkIn)
                        <span class="badge badge-warning">Checked In</span>
                        <div class="small ">
                            {{ $run->checkIn->created_at->format('d-M H:i') }}
                        </div>
                    @else
                        <span class="badge badge-secondary">Not Started</span>
                    @endif
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
                       @if(!$isApproved && $canApproveVisit)
                            <form method="POST"
                                action="{{ route('missions.runs.approve', $run->id) }}"
                                class="d-inline js-confirm-approve-visit">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">
                                    Approve Visit
                                </button>
                            </form>

                        <button type="button"
                                    class="btn btn-sm btn-warning js-run-reschedule"
                                    data-run-id="{{ $run->id }}"
                                    style="border-radius:5px;"
                                    data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                            <i class="fa fa-edit" > reschedule</i>
                         </button>


                        @elseif(!$isApproved && !$canApproveVisit)
                        <span class="badge badge-warning">Waiting Approval</span>


                        @elseif($isPic)
                        <button type="button"
                                class="btn btn-sm btn-light btn-start-run"
                                data-run-id="{{ $run->id }}"
                                style="border-radius:5px;"
                                data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                        <i class="fa fa-play" style="color:#132A72;"> Start</i>
                        </button>
                        @endif
                    @elseif((int)$run->status === 3)
                    <a href="{{ route('missions.runs.show', $run->id) }}"
                    class="btn btn-sm btn-success"
                    style="border-radius:5px;">
                    Go Visiting
                    </a>

                    <button type="button"
                                    class="btn btn-sm btn-warning js-run-reschedule"
                                    data-run-id="{{ $run->id }}"
                                    style="border-radius:5px;"
                                    data-run-code="{{ $run->code ?? ('RUN-'.$run->id) }}">
                            <i class="fa fa-edit" > reschedule</i>
                    </button>
                    @elseif(in_array($role, ['admin','nsm','am']) && $run->status == 6)
                        <a href="{{ route('missions.runs.show', ['run' => $run->id, 'validation_mode' => 1]) }}"
                        class="btn btn-sm btn-success">
                            Validate Visit
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


{{-- // ADD TASK PANEL --}}
<div id="addTaskPanel" style="display:none;" class="mb-3">
    <div class="card border">
        <div class="card-body">
            <div class="mb-2 font-weight-bold">Select Tasks to Add</div>

            <div id="availableTaskWrap">
                Loading...
            </div>

            <button class="btn btn-sm btn-success mt-2 js-submit-add-task">
                Add Selected
            </button>
        </div>
    </div>
</div>


{{-- TASK LIST PANEL --}}
<div class="col-12 mb-4" id="missionTasksPanel" style="display:none;">
  <div class="card shadow border-0 mb-4" style="border-radius:1.25rem; height:420px;">
    <div class="card-body d-flex flex-column">

      <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="h6 font-weight-bold text-uppercase mb-0">
          Tasks
        </div>

        <div class="d-flex align-items-center" style="gap:10px;">
          <div class="small text-muted" id="runTasksTitle">
            Click <b>Detail</b> from the visit to show data...
          </div>


          <button type="button" class="btn btn-sm btn-light" id="btnCloseMissionTasksPanel">
            <i class="fa fa-times"></i>
          </button>
        </div>
      </div>

      <div id="runTasksWrap" class="flex-fill overflow-auto text-center text-muted">
        <div class="py-5">
          Click <b>Detail</b> on the Visit List to load tasks here.
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


<div class="modal fade" id="taskRefModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title" id="taskRefModalTitle">Task Reference Detail</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body" id="taskRefModalBody">
        Loading...
      </div>
    </div>
  </div>
</div>

{{-- //task detail modal --}}

<div class="modal fade" id="taskDetailModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Task Detail</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body" id="taskDetailModalBody">
        <div class="text-center text-muted py-4">Loading...</div>
      </div>
    </div>
  </div>
</div>



@include('modal._mission_detail')
@include('modal._mission_schedul_modal')
@include('modal.reuseable._reschedule_visit')
@endsection

@push('js')

@include('modal.modalJS._mission_pool_js');
@include('modal.modalJS._confirm_validation_js')
@include('modal.reuseable.reuseJS._reschedule_visit_js')
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script>
  window.missionPoolScheduleUrl = @json(route('missions.pool.schedule'));
  window.csrfToken = @json(csrf_token());
</script>


<script>
    $(function(){

        const availableUrl = "{{ route('missions.run.availableTasks', ['run'=>'__RUN__']) }}";
        const addUrl = "{{ route('missions.run.addTasks', ['run'=>'__RUN__']) }}";

        let currentRunId = null;

        // open add panel
        $(document).on('click', '.js-open-add-task', function(){
            currentRunId = $(this).data('run-id');

            $('#addTaskPanel').slideDown();
            $('#availableTaskWrap').html('Loading...');

            $.get(availableUrl.replace('__RUN__', currentRunId), function(html){
                $('#availableTaskWrap').html(html);
            });
        });

        // submit add task
        $(document).on('click', '.js-submit-add-task', function(){

            let selected = [];

            $('.js-task-checkbox:checked').each(function(){
                selected.push($(this).val());
            });

            if (!selected.length) {
                alert('Select at least one task');
                return;
            }

            $.post(addUrl.replace('__RUN__', currentRunId), {
                _token: "{{ csrf_token() }}",
                task_ids: selected
            }, function(){
                location.reload(); // simple first
            });
        });

        // remove task
        $(document).on('click', '.js-remove-task', function(){

            const taskId = $(this).data('task-id');

            if (!confirm('Remove this task from visit?')) return;

            $.post("{{ route('missions.task.removeFromRun', ['task'=>'__TASK__']) }}".replace('__TASK__', taskId), {
                _token: "{{ csrf_token() }}"
            }, function(){
                location.reload();
            });
        });

    });
    </script>


<script>
$(function () {
    const taskDetailUrl = "{{ route('missions.task.detail', ['task' => '__TASK_ID__']) }}";

    $(document).on('click', '.js-view-task-detail', function () {
        const taskId = $(this).data('task-id');
        const url = taskDetailUrl.replace('__TASK_ID__', taskId);

        $('#taskDetailModalBody').html(
            '<div class="text-center text-muted py-4">Loading...</div>'
        );

        $.get(url, function (html) {
            $('#taskDetailModalBody').html(html);
        }).fail(function (xhr) {
            console.error(xhr.responseText);
            $('#taskDetailModalBody').html(
                '<div class="text-center text-danger py-4">Failed to load task detail.</div>'
            );
        });
    });
});
</script>

@endpush
