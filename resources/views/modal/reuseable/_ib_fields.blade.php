{{-- components/installbase/_ib_fields.blade.php --}}
@php
  /**
   * Expected variables:
   * - $ib : Installbase model (with relations loaded)
   * - $mode : 'view' | 'form' (optional)
   * - $idPrefix : string for DOM id prefix (optional) ex: 'md_' to match your modal ids
   */
  $mode = $mode ?? 'view';
  $idPrefix = $idPrefix ?? ''; // if you want md_ ids, pass 'md_'
@endphp

<div class="row">
  <div class="col-md-6 mb-2">
    <div class="small text-muted">IB Code</div>

    {{-- code must disabled (your request) --}}
    @if($mode === 'form')
      <input type="text"
             class="form-control form-control-sm"
             value="{{ $ib->installbase_code ?? '' }}"
             disabled>
    @else
      <div class="font-weight-bold" id="{{ $idPrefix }}installbase_code">
        {{ $ib->installbase_code ?? '-' }}
      </div>
    @endif
  </div>
</div>

<div class="row">

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Province</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}province">
      {{ $ib->hospital?->province?->name ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">City</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}city">
      {{ $ib->hospital?->city ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Hospital</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}hospital">
      {{ $ib->hospital?->name ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Department</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}department">
      {{ $ib->department ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">PIC to Recall</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}pic_to_recall">
      {{ $ib->pic_to_recall ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Department Phone</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}department_phone">
      {{ $ib->department_phone ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Brand</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}brand">
      {{ $ib->product?->brand?->name ?? $ib->brand ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Category</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}category">
      {{ $ib->product?->category?->name ?? $ib->category ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Model / Type</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}model_type">
      {{ $ib->product?->model_type ?? $ib->model_type ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Serial Number</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}serial_number">
      {{ $ib->serial_number ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Installation Date</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}installation_date">
      {{ $ib->installation_date ? \Carbon\Carbon::parse($ib->installation_date)->format('d-M-y') : '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">Installation Status</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}installation_status">
      {{ $ib->installbase_status ?? $ib->installation_status ?? '-' }}
    </div>
  </div>

  <div class="col-md-6 mb-2">
    <div class="small text-muted">End Of Warranty</div>
    <div class="font-weight-bold" id="{{ $idPrefix }}end_of_warranty">
      {{ $ib->end_of_warranty ? \Carbon\Carbon::parse($ib->end_of_warranty)->format('d-M-y') : '-' }}
    </div>
  </div>

</div>
