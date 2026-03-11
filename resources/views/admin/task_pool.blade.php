@extends('layout.backend.app',[
  'title' => 'Task Pool',
  'pageTitle' => '',
])

@section('content')
<div class="container-fluid">

 <div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
    <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
      <div class="row align-items-center">

        <div class="col-12 col-lg-4 d-flex align-items-center mb-3 mb-lg-0">
          <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
          <div class="text-white font-weight-bold text-uppercase" style="letter-spacing:1px;">
            Task<br>Pool
          </div>
        </div>

        <div class="col-12 col-lg-8">
          <form id="taskPoolFilterForm" method="GET" action="{{ route('missions.taskPool') }}">
            <div class="form-row align-items-center">

                <div class="col-12 col-md-4 mb-2 mb-md-0">
                <select id="province_id"
                        class="form-control form-control-sm"
                        name="province_id">
                    <option value="">All Provinces</option>
                    @foreach($provinces as $p)
                    <option value="{{ $p->id }}" {{ (string)$provinceId === (string)$p->id ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                    @endforeach
                </select>
                </div>

                <div class="col-12 col-md-4 mb-2 mb-md-0">
                <select id="hospital_id"
                        class="form-control form-control-sm"
                        name="hospital_id"
                        {{ !$provinceId ? 'disabled' : '' }}>
                    <option value="">All Hospitals</option>
                </select>
                </div>

                <div class="col-6 col-md-2 mb-2 mb-md-0">
                <button type="submit"
                        class="btn btn-light btn-sm btn-block font-weight-bold">
                    Filter
                </button>
                </div>

                <div class="col-6 col-md-2">
                    <button type="button" class="btn btn-outline-light btn-sm" id="btnClearFilter">
                    Clear Filter
                    </button>
                </div>

            </div>
            </form>

        {{-- ACTION BUTTONS --}}
      <div class="mt-3 d-flex flex-wrap" style="gap:10px;">
        <button type="button" class="btn btn-success btn-sm" id="btnShowChecklist">
          <i class="fas fa-tasks mr-1"></i> Setup Visit
        </button>

        <button type="button" class="btn btn-secondary btn-sm d-none" id="btnHideChecklist">
          <i class="fas fa-eye-slash mr-1"></i> Hide Checklist
        </button>

        {{-- <button type="button" class="btn btn-warning btn-sm" id="btnOpenCreateMissionRunModal">
          <i class="fas fa-plus mr-1"></i> Create Mission
        </button> --}}

        <button type="button" class="btn btn-success btn-sm d-none" id="btnOpenAddToMissionRunModal">
          <i class="fas fa-arrow-right mr-1"></i> Plan Visit
        </button>

        <a href="{{ route('missions.pool') }}" type="button" class="btn btn-info btn-sm" >
          <i class="fas fa-calendar-check mr-1"></i> Schedule View
        </a>


      </div>

      </div>

      </div>

    </div>
  </div>

  {{-- TASK POOL ACCORDION --}}

  <div class="accordion" id="taskPoolAccordion">

  @php $i = 0; @endphp

  @forelse($grouped as $hospitalId => $items)
    @php
      $i++;
      $hospital = $items->first()->hospital ?? null;
      $headerId = "heading{$i}";
      $collapseId = "collapse{$i}";

      $hospitalTitle = $hospital
          ? strtoupper($hospital->name).' - '.strtoupper($hospital->city ?? '')
          : 'UNKNOWN / NO HOSPITAL';

      $superUrgentCount = $items->where('priority_level', 'Super Urgent')->count();
      $urgentCount = $items->where('priority_level', 'Urgent')->count();
      $pentingCount = $items->where('priority_level', 'Penting')->count();
    @endphp

    <div class="card shadow mb-2" style="border-radius: 1rem;">
      <div class="card-header" id="{{ $headerId }}" style="border-radius: 1rem;">
        <button class="btn btn-link text-left w-100 d-flex justify-content-between align-items-center"
                type="button"
                data-toggle="collapse"
                data-target="#{{ $collapseId }}"
                aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                aria-controls="{{ $collapseId }}">

          <div>
            <div class="font-weight-bold text-uppercase">
              {{ $hospitalTitle }}
            </div>
            <div class="mt-1">
              <span class="badge badge-primary">{{ $items->count() }} Tasks</span>

              @if($superUrgentCount > 0)
                <span class="badge badge-danger">{{ $superUrgentCount }} Super Urgent</span>
              @endif

              @if($urgentCount > 0)
                <span class="badge badge-warning">{{ $urgentCount }} Urgent</span>
              @endif

              @if($pentingCount > 0)
                <span class="badge badge-info">{{ $pentingCount }} Penting</span>
              @endif
            </div>
          </div>

          <span class="text-muted small">
            Click to expand
          </span>
        </button>
      </div>

      <div id="{{ $collapseId }}"
           class="collapse {{ $i === 1 ? 'show' : '' }}"
           aria-labelledby="{{ $headerId }}"
           data-parent="#taskPoolAccordion">

        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
              <thead class="thead-light text-uppercase">
                <tr>
                  <th style="width:34px;" class="js-col-check d-none"></th>
                  <th>Dept</th>
                  <th>Task Ref</th>
                  <th>Purpose</th>
                  <th>Expected Outcome</th>
                  <th>Deadline</th>
                  <th>Priority</th>
                </tr>
              </thead>
              <tbody>
                @foreach($items as $t)
                  <tr>
                    <td class="js-col-check d-none align-middle text-center">
                      <input type="checkbox"
                             class="js-task-check"
                             value="{{ $t->id }}"
                             data-hospital-id="{{ $t->hospital_id }}">
                    </td>


                    <td class="align-middle">{{ $t->department ?? '-' }}</td>
                    <td class="align-middle text-uppercase">{{ $t->task_reference ?? '-' }}</td>
                     <td class="align-middle">{{ $t->task_purpose ?? '-' }}</td>
                     <td class="align-middle">{{ $t->expected_outcome ?? '-' }}</td>
                    <td class="align-middle">
                      {{ $t->deadline ? \Carbon\Carbon::parse($t->deadline)->format('d-M-y') : '-' }}
                    </td>

                    <td class="align-middle">
                      @php
                        $priority = $t->priority_level ?? '-';
                      @endphp

                      @if($priority === 'Super Urgent')
                        <span class="badge badge-danger">{{ $priority }}</span>
                      @elseif($priority === 'Urgent')
                        <span class="badge badge-warning">{{ $priority }}</span>
                      @elseif($priority === 'Penting')
                        <span class="badge badge-info">{{ $priority }}</span>
                      @else
                        <span class="badge badge-secondary">{{ $priority }}</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  @empty
    <div class="alert alert-info">No tasks in pool.</div>
  @endforelse

</div>


</div>

{{-- MODAL: CREATE MISSION RUN --}}
<div class="modal fade" id="createMissionRunModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Create Mission</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="alert alert-warning small mb-3">
          Mission hanya untuk <b>1 hospital</b>. Pastikan filter hospital sudah dipilih.
        </div>

        <div class="row">
          <div class="col-md-6 mb-2">
            <label class="small text-uppercase">Hospital</label>
            <select class="form-control" id="cmr_hospital_id">
              <option value="">Select hospital</option>
              @foreach($hospitals as $h)
                <option value="{{ $h->id }}">{{ $h->name }} - {{ $h->city }}</option>
              @endforeach
            </select>
            <small class="text-muted">Auto akan set ke hospital dari filter kalau ada.</small>
          </div>

          <div class="col-md-6 mb-2">
            <label class="small text-uppercase">Deadline Mission</label>
            <input type="date" class="form-control" id="cmr_deadline">
          </div>

          <div class="col-md-6 mb-2">
            <label class="small text-uppercase">Task and Scheduling Purpose</label>
            <input type="text" class="form-control" id="cmr_purpose" placeholder="ex: Weekly mission run">
          </div>

          <div class="col-md-6 mb-2">
            <label class="small text-uppercase">Person In Charge (PIC)</label>
            <select class="form-control" id="cmr_pic_user_id">
              <option value="">(optional, can assign later)</option>
              @foreach($pics ?? [] as $u)
                <option value="{{ $u->id }}">{{ $u->name }}</option>
              @endforeach
            </select>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-primary" id="btnCreateMissionRun">Create</button>
      </div>
    </div>
  </div>
</div>

{{-- MODAL: ADD TASKS TO MISSION RUN --}}
<div class="modal fade" id="addToMissionRunModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Add Tasks to Mission</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <div class="mb-2 small text-muted">
          Selected tasks: <b id="am_count">0</b>
        </div>

        <div class="alert alert-info small">
          Task yang bisa ditambahkan harus dari <b>hospital yang sama</b>.
        </div>

        <label class="small text-uppercase">Choose Mission (Same Hospital)</label>

        <select id="mission_run_id" name="mission_run_id" class="form-control form-control-sm" required>
        <option value="">Select Mission</option>
        </select>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button class="btn btn-success" id="btnConfirmAddToMissionRun">Add Now</button>
      </div>
    </div>
  </div>
</div>

@endsection

@push('js')
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>

<script>
  window.csrfToken = @json(csrf_token());

  // dependent dropdown: only provinces/hospitals that exist in task pool
  window.hospitalByProvinceUrl = @json(route('admin.getHospitalsByProvince', ['provinceId' => '__ID__']));

  // endpoints
  window.createMissionRunUrl = @json(route('missionruns.store'));
  window.bulkAddToMissionRunUrl = @json(route('missionruns.bulkAddToMissionRun'));
window.taskPoolHospitalsUrl = @json(route('missions.taskPoolHospitalsByProvince', ['provinceId' => '__ID__']));

  window.missionRunsByHospitalUrl = @json(route('missionruns.byHospital', ['hospitalId' =>$hospitalId]));

window.selectedHospitalId = @json($hospitalId ?? '');
  // default from filter
    console.log('Selected hospital ID from filter:', window.selectedHospitalId);
</script>

@include('modal.modalJS._task_pool_js')
@include('modal.modalJS._taskpool_filter_js')
@endpush
