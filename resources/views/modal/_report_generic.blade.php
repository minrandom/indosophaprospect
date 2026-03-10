<div class="mb-3">
  <div class="h6 text-uppercase">Report</div>
</div>

<div class="form-group">
  <label class="small text-uppercase text-muted">Report Result</label>
  <textarea name="generic_report" class="form-control" rows="6" required>{{ old('generic_report') }}</textarea>
  @error('generic_report') <small class="text-danger">{{ $message }}</small> @enderror
</div>
