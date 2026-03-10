{{-- Schedule Mission Run Modal --}}
<div class="modal fade" id="scheduleRunModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Schedule Mission</h5>
        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="sr_run_id">

        <div class="mb-2">
          <div><b id="sr_run_code">-</b></div>
          <div class="small text-muted">Set schedule date, hour, and duration</div>
        </div>

        <div class="row">
          <div class="col-md-4">
            <label class="small text-uppercase">Date</label>
            <input type="date" id="sr_date" class="form-control">
          </div>

          <div class="col-md-4">
            <label class="small text-uppercase">Hour</label>
            <select id="sr_time" class="form-control">
              @for($h=8; $h<=17; $h++)
                @php $hh = str_pad($h,2,'0',STR_PAD_LEFT); @endphp
                <option value="{{ $hh }}:00">{{ $hh }}:00</option>
              @endfor
            </select>
            <small class="text-muted">Hour only (no minutes)</small>
          </div>

          <div class="col-md-4">
            <label class="small text-uppercase">Duration</label>
            <select id="sr_duration" class="form-control">
              <option value="60">1 hour</option>
              <option value="120">2 hours</option>
              <option value="180">3 hours</option>
              <option value="240">4 hours</option>
              <option value="300">5 hours</option>
              <option value="360">6 hours</option>
              <option value="420">7 hours</option>
              <option value="480">8 hours</option>
            </select>
          </div>
        </div>

      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" id="btnSaveRunSchedule" class="btn btn-primary">
          Save Schedule
        </button>
      </div>
    </div>
  </div>
</div>
