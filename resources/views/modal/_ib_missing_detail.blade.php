<div class="modal fade" id="missingDetailModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content" style="border-radius:1rem;">

      <div class="modal-header">
        <h5 class="modal-title font-weight-bold text-uppercase">
          Installbase Missing Detail
        </h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">

        {{-- Missing Data Section --}}
        <div class="mb-3">
          <div class="small text-muted text-uppercase mb-2">Missing Data</div>
          <div id="md_missing_badges"></div>
        </div>

        <hr>

        {{-- Installbase Detail Section --}}
        <div class="small text-muted text-uppercase mb-2">
            Installbase Detail
        </div>

        <div class="row">
            <div class="col-md-6 mb-2">
              <div class="small text-muted">IB Code</div>
              <div class="font-weight-bold" id="md_installbase_code"></div>
            </div>
        </div>
        <div class="row">
          <div class="col-md-6 mb-2">
            <div class="small text-muted">Province</div>
            <div class="font-weight-bold" id="md_province"></div>
          </div>
          <div class="col-md-6 mb-2">
              <div class="small text-muted">City</div>
              <div class="font-weight-bold" id="md_city"></div>
            </div>


          <div class="col-md-6 mb-2">
            <div class="small text-muted">Hospital</div>
            <div class="font-weight-bold" id="md_hospital"></div>
          </div>

            <div class="col-md-6 mb-2">
              <div class="small text-muted">Department</div>
              <div class="font-weight-bold" id="md_department"></div>
            </div>

          <div class="col-md-6 mb-2">
              <div class="small text-muted">PIC to Recall</div>
              <div class="font-weight-bold" id="md_pic_to_recall"></div>
            </div>

            <div class="col-md-6 mb-2">
              <div class="small text-muted">Department Phone</div>
              <div class="font-weight-bold" id="md_department_phone"></div>
            </div>

          <div class="col-md-6 mb-2">
            <div class="small text-muted">Brand</div>
            <div class="font-weight-bold" id="md_brand"></div>
          </div>

          <div class="col-md-6 mb-2">
            <div class="small text-muted">Category</div>
            <div class="font-weight-bold" id="md_category"></div>
          </div>

          <div class="col-md-6 mb-2">
            <div class="small text-muted">Model / Type</div>
            <div class="font-weight-bold" id="md_model_type"></div>
          </div>


          <div class="col-md-6 mb-2">
            <div class="small text-muted">Serial Number</div>
            <div class="font-weight-bold" id="md_serial_number"></div>
          </div>


          <div class="col-md-6 mb-2">
            <div class="small text-muted">Installation Date</div>
            <div class="font-weight-bold" id="md_installation_date"></div>
          </div>

          <div class="col-md-6 mb-2">
            <div class="small text-muted">Installation Status</div>
            <div class="font-weight-bold" id="md_installation_status"></div>
          </div>

          <div class="col-md-6 mb-2">
            <div class="small text-muted">End Of Warranty</div>
            <div class="font-weight-bold" id="md_end_of_warranty"></div>
          </div>

        </div>

      </div>

      <div class="modal-footer">
        <form id="createTaskForm" method="POST" action="#">
          @csrf
          <input type="hidden" name="installbase_id" id="md_installbase_id">
            <input type="hidden" id="md_hospital_id">
            <input type="hidden" id="md_department_input">
          <button type="button"
            class="btn btn-warning btn-block font-weight-bold"
            id="btnAutoCreateMission">
            Add to Task
          </button>
        </form>

        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          Close
        </button>
      </div>

    </div>
  </div>
</div>
