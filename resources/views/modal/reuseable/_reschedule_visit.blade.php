<div class="modal fade" id="rescheduleRunModal" tabindex="-1">
  <div class="modal-dialog">
    <form method="POST" id="rescheduleRunForm">
      @csrf

      <div class="modal-content" style="border-radius:1rem;">
        <div class="modal-header">
          <h5 class="modal-title">Reschedule Visit</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="small text-muted mb-3" id="rescheduleRunCode"></div>

          <div class="form-group">
            <label>Schedule Date</label>
            <input type="date" name="schedule_date" id="rs_schedule_date" class="form-control" required>
            <input type="hidden" name="swap_with_run_id" id="swap_with_run_id">
          </div>

          <div class="form-group">
            <label>Schedule Time</label>
            <select name="schedule_time" id="rs_schedule_time" class="form-control" required>
                <option value="">Select Time</option>
                <option value="08:00">08:00</option>
                <option value="10:00">10:00</option>
                <option value="12:00">12:00</option>
                <option value="14:00">14:00</option>
                <option value="16:00">16:00</option>
                <option value="18:00">18:00</option>
                <option value="20:00">20:00</option>
            </select>
          </div>

          <div class="form-group">
            <label>Duration</label>
            <select name="schedule_duration_minutes" id="rs_duration" class="form-control" required>
              <option value="">Select Duration</option>
              <option value="120">2 Hours</option>
              <option value="240">4 Hours</option>
              <option value="360">6 Hours</option>
              <option value="480">8 Hours</option>
            </select>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-warning">Save Reschedule</button>
        </div>
      </div>
    </form>
  </div>
</div>
