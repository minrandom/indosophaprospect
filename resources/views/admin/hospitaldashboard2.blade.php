@extends('layout.backend.app',[
  'title' => 'Hospital Dashboard',
  'pageTitle' => 'Hospital Dashboard',
])

@section('content')

{{-- TOP HEADER STRIP --}}
<div class="card shadow mb-4">
  <div class="card-body">
    <div class="row align-items-center">

      {{-- LEFT SIDE --}}
      <div class="col-12 col-lg-3">
        <div class="h5 font-weight-bold text-uppercase mb-1">
          {{ $hospital->name }}
        </div>

        <div class="text-muted mb-2">
          {{ $hospital->city }}
        </div>
      </div>
      <div class="col-12 col-lg-3">
        {{-- TARGET --}}
        <div>
          <span class="small text-muted text-uppercase">Target</span><br>
          <span class="font-weight-bold">
            {{ $hospital->target ?? '-' }}
          </span>
        </div>
      </div>

      {{-- RIGHT SIDE: SPECIALITIES --}}
      <div class="col-12 col-lg-3 text-lg-right mt-3 mt-lg-0">
        <div class="small text-muted text-uppercase">Specialities</div>
        <div class="font-weight-bold text-uppercase">
          {{ implode(', ', $top['specialities']) }}
        </div>
      </div>

      {{-- RIGHT SIDE: VISITS --}}
      <div class="col-12 col-lg-3 text-lg-right mt-3 mt-lg-0">
        <div class="small text-muted text-uppercase">No Visits</div>
        <div class="h4 mb-0 font-weight-bold">
          {{ $top['visits'] }}
        </div>
      </div>

    </div>
  </div>
</div>



<div class="row">

  {{-- LEFT: IB SUMMARY --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">IB Summary</h6>

        {{-- Business Unit dropdown (dummy) --}}
        <div>
          <select class="form-control form-control-sm">
            <option selected>Business Unit</option>
            <option>BU A</option>
            <option>BU B</option>
          </select>
        </div>
      </div>

      <div class="card-body">
        <div class="row">

          {{-- Total equipment --}}
          <div class="col-12 col-md-5 mb-3">
            <div class="card border-left-primary shadow-sm h-100">
              <div class="card-body text-center">
                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                  Total Equipment
                </div>
                <div class="display-4 font-weight-bold">{{ $top['total_equipment'] }}</div>
              </div>
            </div>
          </div>

          {{-- Under contract donut --}}
          <div class="col-12 col-md-7 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">% Under Contract</div>
                <div style="height:170px;">
                  <canvas id="hospitalUnderContractChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          {{-- Warranties --}}
          <div class="col-12 col-md-6 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">Warranties</div>
                <div style="height:220px;">
                  <canvas id="hospitalWarrantiesChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          {{-- Departments --}}
          <div class="col-12 col-md-6 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">Departments</div>
                <div style="height:220px;">
                  <canvas id="hospitalDepartmentsChart"></canvas>
                </div>
              </div>
            </div>
          </div>

        </div> {{-- row --}}
      </div>
    </div>
  </div>

  {{-- RIGHT: KEY PEOPLE --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Key People</h6>
      </div>

      <div class="card-body p-0">

        <div class="table-responsive">
          <table class="table table-sm mb-0">
            <thead class="thead-light">
              <tr class="text-uppercase">
                <th style="width:70px;">&nbsp;</th>
                <th>Name</th>
                <th>Role</th>
                <th>Department</th>
                <th>Phone</th>
                <th>Influence Type</th>
              </tr>
            </thead>
            <tbody>
              @foreach($top['key_people'] as $kp)
              <tr>
                <td class="text-center align-middle">
                  <img src="https://via.placeholder.com/48"
                       class="rounded-circle" width="48" height="48" alt="avatar">
                </td>
                <td class="align-middle">{{ $kp['name'] }}</td>
                <td class="align-middle text-uppercase">{{ $kp['role'] }}</td>
                <td class="align-middle text-uppercase">{{ $kp['department'] }}</td>
                <td class="align-middle">{{ $kp['phone'] }}</td>
                <td class="align-middle text-uppercase">{{ $kp['influence'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

</div>

<div class="row">
{{-- LEFT: EQUIPMENT LIST --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Equipment List</h6>

        {{-- Business Unit Dropdown (dummy) --}}
        <div style="min-width: 180px;">
          <select class="form-control form-control-sm" id="bu_equipment">
            <option value="" selected>Business Unit</option>
            <option value="BU1">BU 1</option>
            <option value="BU2">BU 2</option>
            <option value="BU3">BU 3</option>
          </select>
        </div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light text-uppercase">
              <tr>
                <th>Equipment</th>
                <th>Model</th>
                <th>Age</th>
                <th>Status</th>
                <th>Condition</th>
                <th>Warranty</th>
              </tr>
            </thead>

            <tbody>
              {{-- Dummy rows (replace with @foreach later) --}}
              <tr>
                <td>Ventilator</td>
                <td>Model X</td>
                <td>3y</td>
                <td>Active</td>
                <td>Good</td>
                <td>Active</td>
              </tr>
              <tr>
                <td>ECG</td>
                <td>Model A</td>
                <td>5y</td>
                <td>Active</td>
                <td>Fair</td>
                <td>Ending Soon</td>
              </tr>
              <tr>
                <td>Defibrillator</td>
                <td>Model D</td>
                <td>8y</td>
                <td>Inactive</td>
                <td>Needs Repair</td>
                <td>Finished</td>
              </tr>

              {{-- If later no data --}}
              {{-- <tr>
                <td colspan="6" class="text-center text-muted">No equipment found</td>
              </tr> --}}
            </tbody>
          </table>
        </div>
      </div>

    </div>
  </div>


  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Prospects</h6>
      </div>
  <div class="card-body">

    {{-- KPIs --}}
    <div class="row">
      <div class="col-6 mb-3">
        <div class="card border-left-primary shadow-sm h-100">
          <div class="card-body py-2">
            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Prospects</div>
            <div class="h4 mb-0 font-weight-bold text-gray-800">
              {{ $prospectWidgets['total'] }}
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 mb-3">
        <div class="card border-left-success shadow-sm h-100">
          <div class="card-body py-2">
            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Value</div>
            <div class="h6 mb-0 font-weight-bold text-gray-800">
              Rp {{ number_format($prospectWidgets['value'], 0, ',', '.') }}
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Charts --}}
    <div class="row">
      <div class="col-12 col-md-6 mb-3">
        <div class="card shadow-sm h-100">
          <div class="card-body">
            <div class="text-xs font-weight-bold text-uppercase mb-2">By Temperature</div>
            <div style="height:220px;">
              <canvas id="prospectTempPie"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-12 col-md-6 mb-3">
        <div class="card shadow-sm h-100">
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
</div>

{{-- BOTTOM SECTION --}}
<div class="row">

  {{-- OPPORTUNITIES --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Opportunities</h6>

        {{-- Print icon (optional) --}}
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
          <i class="fas fa-print"></i>
        </button>
      </div>

      <div class="card-body">

        {{-- COUNT LINE --}}
        @php
          $opportunitiesCount = $opportunitiesCount ?? 23; // dummy
          $opportunitiesRows = $opportunitiesRows ?? [
            ['type' => 'Contract Offer', 'equipment' => 'Ventilator Servo I', 'reason' => 'No Contract'],
            ['type' => 'Replacement', 'equipment' => 'Monitor', 'reason' => '12 Y Old'],
            ['type' => 'Consumable', 'equipment' => 'AHU', 'reason' => 'High Usage'],
          ];
        @endphp

        <div class="text-center mb-3">
          <span class="h6 text-uppercase">You have</span>
          <span class="h4 font-weight-bold text-success mx-2">{{ $opportunitiesCount }}</span>
          <span class="h6 text-uppercase">opportunities</span>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light text-uppercase">
              <tr>
                <th style="width: 30%;">Type</th>
                <th style="width: 40%;">Related Equipment</th>
                <th style="width: 30%;">Reason</th>
              </tr>
            </thead>
            <tbody>
              @forelse($opportunitiesRows as $row)
                <tr>
                  <td class="text-uppercase">{{ $row['type'] }}</td>
                  <td class="text-uppercase">{{ $row['equipment'] }}</td>
                  <td class="text-uppercase">{{ $row['reason'] }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">No opportunities</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>

  {{-- ALERT / MISSING DATA --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card shadow h-100">
      <div class="card-header py-3 d-flex align-items-center justify-content-between">
        <h6 class="m-0 font-weight-bold text-primary text-uppercase">Alert</h6>

        {{-- Print icon (optional) --}}
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
          <i class="fas fa-print"></i>
        </button>
      </div>

      <div class="card-body">

        {{-- COUNT LINE --}}
        @php
          $missingCount = $missingCount ?? 60; // dummy
          $missingRows = $missingRows ?? [
            ['equipment' => 'Servo I', 'equipment_no' => 'IB000552', 'missing' => 'Department'],
            ['equipment' => 'Electrocuter', 'equipment_no' => 'IB000583', 'missing' => 'Department, Condition'],
            ['equipment' => 'Operating Table', 'equipment_no' => 'IB000622', 'missing' => 'Last Service, Department'],
          ];
        @endphp

        <div class="text-center mb-3">
          <span class="h6 text-uppercase">You have</span>
          <span class="h4 font-weight-bold text-danger mx-2">{{ $missingCount }}</span>
          <span class="h6 text-uppercase">missing data</span>
        </div>

        {{-- TABLE --}}
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0">
            <thead class="thead-light text-uppercase">
              <tr>
                <th style="width: 35%;">Equipment</th>
                <th style="width: 25%;">Equipment No</th>
                <th style="width: 40%;">Missing Data</th>
              </tr>
            </thead>
            <tbody>
              @forelse($missingRows as $row)
                <tr>
                  <td class="text-uppercase">{{ $row['equipment'] }}</td>
                  <td class="text-uppercase">{{ $row['equipment_no'] }}</td>
                  <td class="text-uppercase">{{ $row['missing'] }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="3" class="text-center text-muted">No missing data</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

      </div>
    </div>
  </div>+

</div>


@endsection

@push('js')
<script>
  (function () {
    if (typeof Chart === 'undefined') {
      console.error('Chart.js not loaded. Check layout includes Chart.min.js once.');
      return;
    }

    // Donut - Under Contract
    new Chart(document.getElementById('hospitalUnderContractChart'), {
      type: 'doughnut',
      data: {
        labels: ['Under Contract', 'Not Under Contract'],
        datasets: [{
          data: @json($top['under_contract']),
          backgroundColor: ['#36b9cc', '#858796'],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { position: 'right' }
      }
    });

    // Horizontal bar - Warranties
    new Chart(document.getElementById('hospitalWarrantiesChart'), {
      type: 'horizontalBar',
      data: {
        labels: ['Active', 'Ending Soon', 'Finished'],
        datasets: [{
          label: 'Count',
          data: @json($top['warranties']),
          backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b'],
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        scales: { xAxes: [{ ticks: { beginAtZero: true } }] }
      }
    });

    // Departments bar
    new Chart(document.getElementById('hospitalDepartmentsChart'), {
      type: 'bar',
      data: {
        labels: @json($top['departments']['labels']),
        datasets: [{
          label: 'Count',
          data: @json($top['departments']['data']),
          backgroundColor: '#4e73df'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
      }
    });
  })();

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
