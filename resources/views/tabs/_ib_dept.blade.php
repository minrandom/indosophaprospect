<div class="row text-white">

  {{-- LEFT SUMMARY + CHARTS --}}
  <div class="col-12 col-lg-6 mb-4">
    <div class="card border-0 p-4" style="border-radius:1.5rem; background:#4E73DF;">

      <div class="row align-items-center">

        {{-- TOTAL COVERED --}}
        <div class="col-6 text-center">
          <div class="small text-uppercase">% Equipment Covered</div>
          <div class="display-4 font-weight-bold">
            {{ $ib['covered_total'] ?? 0 }}
          </div>
        </div>

        {{-- DONUT --}}
        <div class="col-6">
          <div style="height:180px;" class="text-white">
            <canvas id="deptCoveredChart"></canvas>
          </div>
        </div>

      </div>

      <hr class="bg-light">

      {{-- WARRANTIES --}}
      <div class="mb-4">
        <div class="small text-uppercase mb-2">Warranties</div>
        <div style="height:200px;">
          <canvas id="deptWarrantyChart"></canvas>
        </div>
      </div>

      {{-- SERVICE CONTRACTS --}}
      <div>
        <div class="small text-uppercase mb-2">Service Contracts</div>
        <div style="height:200px;">
          <canvas id="deptContractChart"></canvas>
        </div>
      </div>

    </div>
  </div>

  {{-- RIGHT: EQUIPMENT TABLE --}}
  <div class="col-12 col-lg-6">
    <h4 class="text-uppercase text-center mb-4">All Equipments</h4>

    <div class="table-responsive">
      <table class="table table-bordered text-dark">
        <thead>
          <tr>
            <th>Equipment</th>
            <th>Model</th>
            <th>Status</th>
            <th>Coverage</th>
            <th>Coverage Type</th>
          </tr>
        </thead>

        <tbody>
          @forelse(($ib['equipments'] ?? []) as $eq)
            <tr>
              <td>{{ $eq['equipment'] }}</td>
              <td>{{ $eq['model'] }}</td>
              <td>{{ $eq['status'] }}</td>
              <td>{{ $eq['coverage'] }}</td>
              <td>{{ $eq['coverage_type'] }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="text-center">No Equipment Data</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

  </div>

</div>
