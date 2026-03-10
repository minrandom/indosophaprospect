{{-- Schedule Modal --}}
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Add Schedule</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">

        <input type="hidden" id="sm_mission_id">
        <input type="hidden" id="sm_date">
        <input type="hidden" id="sm_time">

        <div class="mb-2">
          <div><b id="sm_mission_code">-</b></div>
          <div class="small text-muted">
            Hospital: <span id="sm_hospital">-</span> |
            PIC: <span id="sm_pic">-</span>
          </div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label class="small text-uppercase">Date</label>
            <input type="date" id="sm_date_input" class="form-control">
          </div>
          <div class="col-md-4">
            <label class="small text-uppercase">Time</label>
            <input type="time" id="sm_time_input" class="form-control" step="1800">
            <small class="text-muted">30-min slots only</small>
          </div>
          <div class="col-md-4">
            <label class="small text-uppercase">Duration</label>
            <select id="sm_duration" class="form-control">
              <option value="30">30 min</option>
              <option value="60">60 min</option>
              <option value="90">90 min</option>
            </select>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnSaveSchedule" class="btn btn-primary">
          Save Schedule
        </button>
      </div>
    </div>
  </div>
</div>




{{-- modal for mission details --}}
<div class="modal fade" id="missionDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Mission Details</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <div id="md_loading" class="text-center text-muted py-4">Loading...</div>

        <div id="md_content" style="display:none;">
          <div class="mb-3">
            <div class="h6 mb-1"><b id="md_code">-</b></div>
            <div class="small text-muted">
              Hospital: <span id="md_hospital">-</span> |
              PIC: <span id="md_pic">-</span> |
              Deadline: <span id="md_deadline">-</span>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 mb-2">
              <div class="small text-uppercase text-muted">Purpose</div>
              <div id="md_purpose" class="font-weight-bold">-</div>
            </div>
            <div class="col-md-6 mb-2">
              <div class="small text-uppercase text-muted">Urgency</div>
              <div id="md_urgency" class="font-weight-bold">-</div>
            </div>
          </div>

          <div class="mb-3">
            <div class="small text-uppercase text-muted">Expected Outcome</div>
            <div id="md_expected" style="white-space:pre-line;">-</div>
          </div>

          <hr>

          <div class="h6 text-uppercase mb-2">History</div>
          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
              <thead class="thead-light text-uppercase">
                <tr>
                  <th style="width:160px;">Time</th>
                  <th style="width:180px;">Action</th>
                  <th>Actor</th>
                  <th>Note</th>
                </tr>
              </thead>
              <tbody id="md_history_body">
                <tr><td colspan="4" class="text-center text-muted">No history</td></tr>
              </tbody>
            </table>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
