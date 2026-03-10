@extends('layout.backend.app',[
  'title' => 'Hospital Overview',
  'pageTitle' => '',
])

@section('content')

{{-- TOP HEADER STRIP --}}
<div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
  <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">
    <div class="row align-items-center">

      {{-- LEFT SIDE COLUMN --}}
<div class="col-12 col-lg-6 mb-3 mb-lg-0">

  <div class="d-flex align-items-center justify-content-between">

    {{-- TITLE (LEFT) --}}
    <div class="d-flex align-items-center">
      <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
      <div class="text-white font-weight-bold text-uppercase"
           style ="letter-spacing:1px;">
        Hospital </br> Overview
      </div>
    </div>

    {{-- DEPARTMENT SEARCH (RIGHT) --}}
    <form id="deptSearchForm" method="GET">
    <div class="form-row align-items-center">

      <div class="col-8">
        <select id="department_id"
                class="form-control form-control-sm"
                name="department_id"
                required>
          <option value="">Select Department</option>
          @foreach($departments as $d)
            <option value="{{ $d->id }}">{{ $d->name }}</option>
          @endforeach
        </select>
      </div>

      <div class="col-4">
        <button type="submit"
                class="btn btn-light btn-sm btn-block font-weight-bold">
          Search
        </button>
      </div>

    </div>
  </form>

  </div>

</div>

      {{-- RIGHT: HOSPITAL INFO --}}
      <div class="col-12 col-lg-6 text-lg-right">

        <div class="text-white font-weight-bold text-uppercase"
             style="letter-spacing:1px;">
          {{ $hospital->name }}
        </div>

        <div class="small text-white-50 text-uppercase">
          {{ $hospital->target ?? '-' }}
        </div>

        <div class="mt-2"
             style="border-top:1px solid rgba(255,255,255,.35); padding-top:6px;">
          <div class="text-white font-weight-bold text-uppercase">
            {{ $hospital->city }}
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<div class="row">

  {{-- LEFT SIDE --}}
  <div class="col-12 col-lg-6 mb-3">

    {{-- KPI ROW --}}
    <div class="row">
      <div class="col-6 mb-3">
        <a href="javascript:void(0)"
            class="text-decoration-none"
            data-toggle="modal"
            data-target="#ibSummaryModal">
            <div class="card shadow border-0 h-100" style="border-radius: 1.25rem; cursor:pointer;">
            <div class="card-body text-center">
                <div class="small text-muted text-uppercase">Total Equipment</div>
                <div class="h3 font-weight-bold">{{ $top['total_equipment'] ?? 53 }}</div>
                <div class="small text-primary mt-1">View IB Summary</div>
            </div>
            </div>
        </a>
        </div>

      <div class="col-6 mb-3">
        <a href="{{ route('hospital.pending.mission', $hospital->id) }}"
            class="text-decoration-none">
        <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
          <div class="card-body text-center">
            <div class="small text-muted text-uppercase">Pending Problems</div>
            <div class="h3 font-weight-bold">{{ $pendingProblems ?? 32 }}</div>
          </div>
        </div>
        </a>
      </div>
    </div>

    {{-- PROSPECTS BIG TILE --}}
    <div class="card shadow border-0 mb-3" style="border-radius: 1.25rem;">
  <div class="card-body">

    {{-- Header --}}
    <div class="d-flex align-items-start justify-content-between mb-3">
      <div class="h6 font-weight-bold text-uppercase mb-0">Prospects</div>
      {{-- optional: filter dropdown later --}}
      {{-- <select class="form-control form-control-sm" style="width:160px;">
        <option selected>Business Unit</option>
      </select> --}}
    </div>

    {{-- KPI Summary --}}
    <div class="row mb-3">
      <div class="col-12 col-md-6 mb-2 mb-md-0">
        <div class="card border-left-primary shadow-sm h-100">
          <div class="card-body py-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total</div>
            <div class="h3 font-weight-bold mb-0">
              {{ $prospectWidgets['total'] ?? 0 }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6">
        <div class="card border-left-success shadow-sm h-100">
          <div class="card-body py-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Value</div>
            <div class="h5 font-weight-bold mb-0">
              Rp {{ number_format($prospectWidgets['value'] ?? 0, 0, ',', '.') }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Charts --}}
    <div class="row">
      <div class="col-12 col-md-6 mb-3">
        <div class="card shadow-sm h-100 border-0" style="border-radius: 1rem;">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-uppercase mb-2">By Temperature</div>
            <div style="height:220px;">
              <canvas id="prospectTempPie"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 mb-3">
        <div class="card shadow-sm h-100 border-0" style="border-radius: 1rem;">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-uppercase mb-2">By Business Unit</div>
            <div style="height:220px;">
              <canvas id="prospectUnitBar"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>

  </div>

  {{-- RIGHT SIDE: KEY PEOPLE TABLE TILE --}}
  <div class="col-12 col-lg-6 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body">

        <div class="d-flex align-items-center justify-content-between mb-2">
          <div class="h6 font-weight-bold text-uppercase mb-0">Key People</div>
          {{-- optional action --}}
          {{-- <a href="#" class="btn btn-sm btn-primary">Add</a> --}}
        </div>

        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead class="thead-light text-uppercase">
              <tr>
                <th style="width:70px;"></th>
                <th>Name</th>
                <th>Role</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Influence</th>
              </tr>
            </thead>
            <tbody>
              @forelse(($top['key_people'] ?? []) as $kp)
                <tr>
                  <td class="align-middle text-center">
                    <img src="https://via.placeholder.com/48"
                         class="rounded-circle" width="48" height="48" alt="avatar">
                  </td>
                  <td class="align-middle">{{ $kp['name'] }}</td>
                  <td class="align-middle text-uppercase">{{ $kp['role'] }}</td>
                  <td class="align-middle text-uppercase">{{ $kp['department'] }}</td>
                  <td class="align-middle">{{ $kp['phone'] }}</td>
                  <td class="align-middle text-uppercase">{{ $kp['influence'] }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="text-center text-muted">No data</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

</div>



@include('modal._hospital_dashboard_modal')





@endsection

@push('js')
@include('modal.modalJS._hospital_dashboard_modal_js')

<script>
  $(function () {
    $('#deptSearchForm').on('submit', function (e) {
      e.preventDefault();

      var deptId = $('#department_id').val();
      if (!deptId) return;

      var hospitalId = @json($hospital->id);
      window.location.href = "{{ url('/department-dashboard') }}/" + hospitalId + "/" + deptId;
    });
  });
</script>


<script>


(function () {
  if (typeof Chart === 'undefined') return;

  // PIE: Temperature
  var tempEl = document.getElementById('prospectTempPie');
  if (tempEl) {
    new Chart(tempEl, {
      type: 'doughnut',
      data: {
        labels: @json($prospectWidgets['temp']['labels']),
        datasets: [{
          data: @json($prospectWidgets['temp']['data']),
          backgroundColor: ['#4e73df', '#1cc88a', '#f6c23e', '#e74a3b', '#36b9cc', '#858796']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { position: 'bottom' }
      }
    });
  }

  // BAR: Unit
  var unitEl = document.getElementById('prospectUnitBar');
  if (unitEl) {
    new Chart(unitEl, {
      type: 'bar',
      data: {
        labels: @json($prospectWidgets['unit']['labels']),
        datasets: [{
          label: 'Count',
          data: @json($prospectWidgets['unit']['data']),
          backgroundColor: '#36b9cc'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          yAxes: [{ ticks: { beginAtZero: true } }]
        }
      }
    });
  }
})();



</script>
@endpush
