<div class="mb-3">
  <div class="h6 text-uppercase font-weight-bold">IB Update Report</div>
  <div class="text-muted small">
    Fill only fields that you updated. Leave unused rows empty.
  </div>
</div>

@php
  // Allowed updateable fields
  $allowedFields = [
      'serial_number'       => 'Serial Number',
      'installation_date'   => 'Installation Date',
      'installation_status' => 'Installation Status',
      'end_of_warranty'     => 'End Of Warranty',
      'department'          => 'Department',
      'department_phone'    => 'Department Phone',
      'pic_to_recall'       => 'PIC To Recall',
  ];

  $rows = old('ib_updates', [
      ['field' => '', 'value' => '', 'note' => ''],
      ['field' => '', 'value' => '', 'note' => ''],
  ]);
@endphp

@foreach($rows as $i => $r)
  <div class="row mb-3 p-2 border rounded">

    {{-- FIELD SELECT --}}
    <div class="col-md-4">
      <label class="small text-muted">Field</label>
      <select class="form-control"
              name="ib_updates[{{ $i }}][field]">
        <option value="">-- Select Field --</option>
        @foreach($allowedFields as $key => $label)
          <option value="{{ $key }}"
            {{ ($r['field'] ?? '') == $key ? 'selected' : '' }}>
            {{ $label }}
          </option>
        @endforeach
      </select>
    </div>

    {{-- VALUE --}}
    <div class="col-md-4">
      <label class="small text-muted">New Value</label>
      <input type="text"
             class="form-control"
             name="ib_updates[{{ $i }}][value]"
             value="{{ $r['value'] ?? '' }}"
             placeholder="Enter new value">
    </div>

    {{-- NOTE --}}
    <div class="col-md-4">
      <label class="small text-muted">Note (Optional)</label>
      <input type="text"
             class="form-control"
             name="ib_updates[{{ $i }}][note]"
             value="{{ $r['note'] ?? '' }}"
             placeholder="Explain if needed">
    </div>

  </div>
@endforeach

@error('ib_updates')
  <small class="text-danger">{{ $message }}</small>
@enderror
