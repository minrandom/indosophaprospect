<div class="modal fade" id="genericReportModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content" style="border-radius:1rem;">
      <div class="modal-header">
        <h5 class="modal-title">Custom Task Report</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <form method="POST" action="{{ route('missions.task.custom.submit') }}">
        @csrf

        <div class="modal-body">
          <div class="mb-3">
            <div class="h6 text-uppercase">Report</div>
          </div>

          <input type="hidden" name="task_id" id="customTaskId" value="">

          <div class="form-group">
            <label class="small text-uppercase text-muted">Report Result</label>
            <textarea name="generic_report" class="form-control" rows="6" required>{{ old('generic_report') }}</textarea>
            @error('generic_report') <small class="text-danger">{{ $message }}</small> @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Submit Report</button>
        </div>
      </form>
    </div>
  </div>
</div>
