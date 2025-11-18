<div class="modal fade" id="activityModal" tabindex="-1">
  <div class="modal-dialog">
    <form id="activity-form" class="modal-content">
      @csrf
      <div class="modal-header"><h5 class="modal-title">New Activity</h5></div>

      <div class="modal-body">
        <div class="form-group">
    <label for="province">Province</label>
    <select name="province_id" id="province" class="form-control" required>
        <option value="">-- Select Province --</option>
        @foreach($provinces as $province)
            <option value="{{ $province->id }}">{{ $province->name }}</option>
        @endforeach
    </select>
</div>

<!-- Hospital Dropdown -->
<div class="form-group">
    <label for="hospital">Hospital</label>
    <select name="hospital_id" id="hospital" class="form-control" required>
        <option value="">-- Select Hospital --</option>
    </select>
</div>
        <div class="form-group">
          <label>Purpose</label>
          <input name="purpose" class="form-control" required>
        </div>
        <div class="form-group">
          <label>Start</label>
          <input type="datetime-local" name="start" class="form-control" required>
        </div>
        <div class="form-group">
          <label>End</label>
          <input type="datetime-local" name="end" class="form-control" required>
        </div>

        <div class="form-group">
          <label>Department</label>
          <select name="departement_id" class="form-control" required>
            @foreach(App\Models\Department::all() as $dept)
              <option value="{{ $dept->id }}">{{ $dept->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary">Save</button>
      </div>
    </form>
  </div>
</div>
