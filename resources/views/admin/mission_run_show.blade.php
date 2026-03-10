@extends('layout.backend.app',[
  'title' => 'Mission',
  'pageTitle' => '',
])

@section('content')
<div class="container-fluid">

  {{-- TOP BAR --}}
  <div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
    <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
      <div class="row align-items-center">
        <div class="col-12 col-lg-6 d-flex align-items-center mb-3 mb-lg-0">
          <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
          <div class="text-white font-weight-bold" style="letter-spacing:1px; font-size:26px;">
            Mission
          </div>
        </div>

        <div class="col-12 col-lg-6 text-lg-right text-white">
          <div class="small text-white-50">Mission Code</div>
          <div class="font-weight-bold">{{ $run->code ?? ('RUN-'.$run->id) }}</div>
          <div class="small text-white-50 mt-1">
            Hospital: <b>{{ $run->hospital?->name ?? '-' }}</b>
            @if($run->hospital?->city) | {{ $run->hospital->city }} @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- MAIN CONTENT --}}
  <div class="row">

    {{-- LEFT: IN MISSION (status 1) --}}
    <div class="col-12 col-lg-8 mb-4">
      <div class="card shadow border-0" style="border-radius: 1.25rem; background:#4E73DF;">
        <div class="card-body text-white">

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="h5 mb-0">Mission Tasks</div>
            <div class="small text-white-50">
              Status Run:
              @if($run->status_mission===3)<b>ON-GOING</b>
              @elseif($run->status_mission===2)<b>SCHEDULED</b>
              @elseif($run->status_mission===1)<b>IDLE</b>
              @elseif($run->status_mission===4)<b>Canceled</b>
              @elseif($run->status_mission===5)<b>Done</b>
              @endif
            </div>
          </div>

          @forelse($inMission as $ref => $items)
            <div class="mb-3">
              <div class="font-weight-bold text-uppercase small mb-2">
                {{ strtoupper($ref) }}: {{ $items->count() }}
              </div>

              <div class="table-responsive">
                <table class="table table-sm mb-0" style="color:#fff;">
                  <thead style="background:#132A72;">
                    <tr class="text-uppercase small">
                      <th>Task Code</th>
                      <th>Purpose</th>
                      <th>Urgency</th>
                      <th>State</th>
                      <th class="text-center">Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($items as $t)
                      <tr>
                        <td>{{ $t->code }}</td>
                        <td>{{ $t->task_purpose ?? '-' }}</td>
                        <td>{{ $t->priority_level ?? '-' }}</td>
                        <td>
                          {{-- task progress inside mission page later --}}
                          <span class="badge badge-secondary">NOT STARTED</span>
                        </td>
                        <td class="text-center">
                          <a href="javascript:void(0)" class="btn btn-sm btn-light" style="border-radius:10px;">
                            Start
                          </a>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          @empty
            <div class="text-white-50">No tasks in this mission.</div>
          @endforelse

        </div>
      </div>
    </div>

    <div class="col-12 col-lg-4 mb-4">
    {{-- RIGHT: TASK POOL (same hospital, status 0) --}}
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem; background:#4E73DF;">
    <div class="card-body text-white">

        <div class="d-flex justify-content-between align-items-center mb-2">
        <div class="h5 mb-0">Task List</div>

        <button type="button"
                id="btnRequestTask"
                class="btn btn-sm btn-light font-weight-bold"
                data-run-id="{{ $run->id }}">
        Request Task
        </button>



        <button type="button"
                id="btnCancelRequest"
                class="btn btn-sm btn-secondary font-weight-bold d-none">
            Cancel
        </button>




        </div>
        <div class="small text-white-50 mb-3">
        * Click "Request Task" to select tasks.
        </div>

        @forelse($taskPool as $ref => $items)
        <div class="mb-3">

            <div class="font-weight-bold text-uppercase small mb-2">
            {{ strtoupper($ref) }}: {{ $items->count() }}
            </div>

            <div class="table-responsive">
            <table class="table table-sm mb-0" style="color:#fff;">
                <thead style="background:#132A72;">
                <tr class="text-uppercase small">
                    <th style="width:40%;">Task Code</th>
                    <th style="width:25%;">Ref</th>
                    <th>Purpose</th>
                    <th>Status</th>
                    <th class="text-center" style="width:60px;">Pick</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $t)
                    <tr class="js-task-row"
                        data-task-id="{{ $t->id }}"
                        data-task-code="{{ $t->code }}"
                        data-task-ref="{{ $t->task_reference }}"
                        data-task-purpose="{{ $t->task_purpose }}">
                    <td class="small">{{ $t->code }}</td>
                    <td class="small text-uppercase">{{ $t->task_reference }}</td>
                    <td class="small">{{ $t->task_purpose ?? '-' }}</td>

                    @if($t->status_mission == 30){
                        <td class="small" style="color:#FFC107;">Requested</td>
                    }
                    @else
                    <td class="small">Avail</td>
                    @endif
                    <td class="text-center">
                        <input type="checkbox"
                            class="js-task-check d-none"
                            value="{{ $t->id }}">
                    </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            </div>

        </div>
        @empty
        <div class="text-white-50 small">
            No task in task pool for this hospital.
        </div>
        @endforelse
         @if(in_array(optional(auth()->user())->role, ['admin','am','nsm']))
            <button type="button"
                    id="btnAddToMission"
                    class="btn btn-sm btn-success font-weight-bold">
            Add To Mission
            </button>
        @endif


    </div>
    </div>
    </div>
  </div>

</div>
@endsection

@push('js')
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script>
$(function () {

  $('#btnAddTasksToRun').on('click', function () {
    const runId = $(this).data('run-id');
    const ids = $('.js-add-task:checked').map(function(){ return $(this).val(); }).get();

    if (!ids.length) {
      Swal.fire({ icon:'warning', title:'No task selected', text:'Please select at least 1 task.' });
      return;
    }

    Swal.fire({
      title: 'Add tasks to mission?',
      html: `Selected: <b>${ids.length}</b> task(s)`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Add',
      cancelButtonText: 'Cancel'
    }).then((r) => {
      if (!r.isConfirmed) return;

      $.ajax({
        url: "{{ url('/missions/runs') }}/" + runId + "/add-tasks",
        method: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          task_ids: ids
        },
        success: function(resp){
          Swal.fire({ icon:'success', title:'Added', timer: 900, showConfirmButton:false });
          setTimeout(() => location.reload(), 650);
        },
        error: function(xhr){
          let msg = 'Failed.';
          try { const j = JSON.parse(xhr.responseText); if (j.message) msg = j.message; } catch(e){}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

});
</script>

<script>
$(function () {

  let requestMode = false;

  function getSelectedTaskIds() {
    return $('.js-task-check:checked').map(function () {
      return $(this).val();
    }).get();
  }

  $('#btnRequestTask').on('click', function () {
    const runId = $(this).data('run-id');

    // 1st click: just enter request mode (show checklist)
    if (!requestMode) {
      requestMode = true;
      $('.js-task-check').removeClass('d-none');
      $('#btnAddToMission').addClass('d-none');
      $('#btnCancelRequest').removeClass('d-none'); // keep your rule
      $(this).text('Request to Add');
      return;
    }

    // 2nd click: submit request
    const ids = getSelectedTaskIds();

    if (!ids.length) {
      Swal.fire({ icon:'warning', title:'No selected task', text:'Please check at least 1 task.' });
      return;
    }

    Swal.fire({
      title: 'Submit request?',
      html: `Selected: <b>${ids.length}</b> task(s)`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Request',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: "/missions/run/" + runId + "/request-tasks",
        method: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          task_ids: ids
        },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Requested', text: r.message || 'OK', timer: 1400, showConfirmButton:false });

          // reset UI mode
          requestMode = false;
          $('.js-task-check').prop('checked', false).addClass('d-none');
          $('#btnAddToMission').removeClass('d-none');
          $('#btnCancelRequest').addClass('d-none');
          $('#btnRequestTask').text('Request Task');

          // easiest: reload so the list reflects status=30 (tasks may disappear from pool)
          setTimeout(() => location.reload(), 800);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          let msg = 'Failed. Check server log.';
          try {
            const json = JSON.parse(xhr.responseText);
            if (json.message) msg = json.message;
          } catch(e) {}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

  $('#btnCancelRequest').on('click', function () {
    requestMode = false;
          $('.js-task-check').prop('checked', false).addClass('d-none');
          $('#btnAddToMission').removeClass('d-none');
          $('#btnCancelRequest').addClass('d-none');
          $('#btnRequestTask').text('Request Task');
  });

  // Add Requested → Move to Mission (Admin/AM/NSM)
  $('#btnAddToMission').on('click', function(){
    const runId = $(this).data('run-id');

    Swal.fire({
      title: 'Add all requested tasks to mission?',
      html: `This will move <b>all</b> tasks with status <b>REQUESTED</b> for this hospital into mission.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes, Add',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: "{{ route('missionrun.addRequestedToMission', $run->id) }}",
        method: "POST",
        data: { _token: "{{ csrf_token() }}" },
        success: function(r){
          Swal.fire({ icon:'success', title:'Added', text:r.message || 'OK', timer:1200, showConfirmButton:false });
          resetRequestUI();
          setTimeout(() => location.reload(), 700);
        },
        error: function(xhr){
          console.error(xhr.responseText);
          let msg = 'Failed. Check server log.';
          try { const j = JSON.parse(xhr.responseText); if (j.message) msg = j.message; } catch(e){}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

});
</script>
@endpush
