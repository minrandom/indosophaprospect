<div class="mb-3">
  <div class="h6 text-uppercase">Prospect Review Report</div>
</div>

<div class="form-group">
  <label class="small text-uppercase text-muted">Prospect Notes</label>
  <textarea name="prospect_notes" class="form-control" rows="6" required>{{ old('prospect_notes') }}</textarea>
  @error('prospect_notes') <small class="text-danger">{{ $message }}</small> @enderror
</div>
