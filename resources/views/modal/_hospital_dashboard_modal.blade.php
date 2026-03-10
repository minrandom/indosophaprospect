<div class="modal fade" id="ibSummaryModal" tabindex="-1" role="dialog" aria-labelledby="ibSummaryModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
    <div class="modal-content border-0" style="border-radius: 1.25rem; overflow:hidden;">

      <div class="modal-header" style="background:#4E73DF;">
        <h5 class="modal-title text-white font-weight-bold text-uppercase" id="ibSummaryModalLabel">IB Summary</h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">

        <div class="row">
          <div class="col-12 col-lg-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">Warranties</div>
                <div style="height:220px;">
                  <canvas id="modalWarrantiesChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">% Under Contract</div>
                <div style="height:220px;">
                  <canvas id="modalContractChart"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-4 mb-3">
            <div class="card shadow-sm h-100">
              <div class="card-body">
                <div class="text-xs font-weight-bold text-uppercase mb-2">Departments</div>
                <div style="height:220px;">
                  <canvas id="modalDepartmentsChart"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>
