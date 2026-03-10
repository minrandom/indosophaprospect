@extends('layout.backend.app',[
	'title' => 'Welcome',
	'pageTitle' => '',
])
@section('content')
<div class="container-fluid">

<div class="card shadow mb-4 border-0" style="border-radius: 1.5rem;">
  <div class="card-body py-4" style="background:#4E73DF; border-radius: 1.5rem;">

    <div class="row align-items-center">

      {{-- LEFT: TITLE --}}
      <div class="col-12 col-lg-4 d-flex align-items-center mb-3 mb-lg-0">
        <div style="width:10px; height:56px; background:#ffffff; margin-right:14px;"></div>
        <div class="text-white font-weight-bold text-uppercase" style="letter-spacing:1px;">
          General<br>Overview
        </div>
      </div>

      {{-- RIGHT: SEARCH FORM --}}
      <div class="col-12 col-lg-8">
        <form id="hospitalSearchForm" method="GET">

          <div class="form-row align-items-center">

            <div class="col-12 col-md-4 mb-2 mb-md-0">
              <select id="province_id"
                      class="form-control form-control-sm"
                      name="province_id"
                      required>
                <option value="">Select Province</option>
                @foreach($provinces as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-12 col-md-5 mb-2 mb-md-0">
              <select id="hospital_id"
                      class="form-control form-control-sm"
                      name="hospital_id"
                      required
                      disabled>
                <option value="">Select Hospital</option>
              </select>
            </div>

            <div class="col-12 col-md-3">
              <button type="submit"
                      class="btn btn-light btn-sm btn-block font-weight-bold">
                Search
              </button>
            </div>

          </div>

        </form>
      </div>

    </div>

  </div>
</div>



  {{-- Dashboards --}}
<div class="row">

  <div class="col-12 col-sm-6 col-lg-3 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">Total Hospital</div>
        <div class="h3 font-weight-bold">{{ $hospitalWithIB ?? 230 }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">Installed Equipments</div>
        <div class="h3 font-weight-bold">{{ $countinstallbase}}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">Opportunity Offers</div>
        <div class="h3 font-weight-bold">{{ $top['opportunity'] ?? 1888 }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-3 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">AVG Installbase Age</div>
        <div class="h3 font-weight-bold">{{ $top['avg_age'] ?? 8 }} Years</div>
      </div>
    </div>
  </div>

</div>
  {{-- Prospect Row --}}
<div class="row">

  <div class="col-12 col-md-6 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">Total Prospect</div>
        <div class="h3 font-weight-bold">{{ $prospectData['count'] }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 mb-3">
    <div class="card shadow border-0 h-100" style="border-radius: 1.25rem;">
      <div class="card-body text-center">
        <div class="small text-muted text-uppercase">Total Value Prospect</div>
        <div class="h3 font-weight-bold">
          Rp {{ number_format($prospectData['value'] ?? 0, 0, ',', '.')}}
        </div>
      </div>
    </div>
  </div>

</div>

  {{-- button quick menus ROW --}}
    <div class="row">
        @foreach($quickMenus as $menu)
            <div class="col-6 col-md-4 col-lg-2 mb-3">
            <a href="" class="text-decoration-none">
                <div class="card shadow h-100 " style="border-radius: 1.25rem;">
                <div class="card-body d-flex align-items-center justify-content-center text-center">
                    <div class="font-weight-bold text-uppercase" style="white-space: pre-line;">
                    {{ $menu['label'] }}
                    </div>
                </div>
                </div>
            </a>
            </div>
        @endforeach



    </div>

  {{-- CHART ROW --}}
  {{-- <div class="row"> --}}

    {{-- WARRANTIES --}}
    {{-- <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">Warranties</h6>
        </div>
        <div class="card-body">
          <div style="height:300px">
            <canvas id="warrantyChart"></canvas>
          </div>
        </div>
      </div>
    </div> --}}

    {{-- UNDER CONTRACT --}}
    {{-- <div class="col-lg-6 mb-4">
      <div class="card shadow">
        <div class="card-header py-3">
          <h6 class="m-0 font-weight-bold text-primary">% Under Contract</h6>
        </div>
        <div class="card-body">
          <div style="height:300px">
            <canvas id="contractChart"></canvas>
          </div>
        </div>
      </div>
    </div> --}}

  {{-- </div> --}}


</div>

{{-- CHART.JS --}}
{{-- <script>
  // WARRANTIES - Horizontal Bar (Chart.js v2)
  new Chart(document.getElementById("warrantyChart"), {
    type: 'horizontalBar',
    data: {
      labels: ["Active", "Ending Soon", "Finished"],
      datasets: [{
        label: "Count",
        data: [300, 600, 1500],
        backgroundColor: [
          "#1cc88a",
          "#f6c23e",
          "#e74a3b"
        ]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        xAxes: [{
          ticks: { beginAtZero: true }
        }]
      }
    }
  });

  // UNDER CONTRACT - Doughnut
  new Chart(document.getElementById("contractChart"), {
    type: 'doughnut',
    data: {
      labels: ["Under Contract", "Not Under Contract"],
      datasets: [{
        data: [55.6, 44.4],
        backgroundColor: ["#36b9cc", "#858796"]
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      legend: {
        position: "bottom"
      }
    }
  });
</script> --}}
@endsection


@push('js')
{{-- <script>
  // This runs AFTER Chart.min.js because @stack('js') is at bottom of layout
  (function () {
    if (typeof Chart === 'undefined') {
      console.error('Chart is undefined. Chart.js is not loaded. Check path in layout.');
      return;
    }

    var w = document.getElementById("warrantyChart");
    var c = document.getElementById("contractChart");

    if (w) {
      new Chart(w, {
        type: 'horizontalBar', // Chart.js v2 (SB Admin 2)
        data: {
          labels: ["Active", "Ending Soon", "Finished"],
          datasets: [{
            label: "Count",
            data: [300, 600, 1500],
            backgroundColor: ["#1cc88a", "#f6c23e", "#e74a3b"]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          scales: {
            xAxes: [{ ticks: { beginAtZero: true } }]
          }
        }
      });
    }

    if (c) {
      new Chart(c, {
        type: 'doughnut',
        data: {
          labels: ["Under Contract", "Not Under Contract"],
          datasets: [{
            data: [55.6, 44.4],
            backgroundColor: ["#36b9cc", "#858796"]
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { position: "bottom" }
        }
      });
    }
  })();
</script> --}}


<script>
  window.hospitalByProvinceUrl = @json(
    route('admin.getHospitalsByProvince', ['provinceId' => '__ID__'])
  );
  window.hospitalDashboardBaseUrl = @json(url('/hospital-dashboard'));
</script>

<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('js/hospital-search.js') }}"></script>


@endpush
