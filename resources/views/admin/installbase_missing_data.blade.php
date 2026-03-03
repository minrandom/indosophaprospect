@extends('layout.backend.app',[
    'title' => 'Under Review Installbase Data',
    'pageTitle' =>'Under Review Installbase Data',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <a type="button" class="btn btn-primary" href="#">
          New Installation
        </a>
        <a type="button" class="btn btn-primary" href="{{ route('admin.installbase') }}">
          All Installbase Data
        </a>
    </div>
    <br><br>

<div class="accordion" id="ibMissingAccordion">

  @php $i = 0; @endphp

  @foreach($grouped as $hospitalId => $items)
    @php
      $i++;
      $hospital = $items->first()->hospital;
      $headerId = "heading{$i}";
      $collapseId = "collapse{$i}";
      $hospitalTitle = $hospital
          ? strtoupper($hospital->name).' - '.strtoupper($hospital->city ?? '')
          : 'UNKNOWN / NO HOSPITAL';
    @endphp

    <div class="card shadow mb-2" style="border-radius: 1rem;">
      <div class="card-header" id="{{ $headerId }}" style="border-radius: 1rem;">
        <button class="btn btn-link text-left w-100 d-flex justify-content-between align-items-center"
                type="button"
                data-toggle="collapse"
                data-target="#{{ $collapseId }}"
                aria-expanded="{{ $i === 1 ? 'true' : 'false' }}"
                aria-controls="{{ $collapseId }}">
          <div class="font-weight-bold text-uppercase">
            {{ $hospitalTitle }}
          </div>

          <span class="badge badge-danger">
            {{ $items->count() }} rows
          </span>
        </button>
      </div>

      <div id="{{ $collapseId }}"
           class="collapse {{ $i === 1 ? 'show' : '' }}"
           aria-labelledby="{{ $headerId }}"
           data-parent="#ibMissingAccordion">

        <div class="card-body">

          <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
              <thead class="thead-light text-uppercase">
                <tr>
                  <th>IB Code</th>
                  <th>Brand</th>
                  <th>Model and Type</th>
                  <th>Product Category</th>
                  <th>Main Serial Number</th>
                  <th>Department</th>
                  <th>Installation Date</th>
                  <th>Missing Columns</th>
                  <th>Table Action</th>
                </tr>
              </thead>
              <tbody>
                     @foreach($items as $row)
                  <tr>
                    <td>{{ $row->installbase_code ?? '-' }}</td>
                    <td>{{ $row->product->brand->name ?? '-' }}</td>
                    <td>{{ $row->product->model_type ?? '-' }}</td>
                    <td>{{ $row->product->category->name ?? '-' }}</td>
                    <td>{{ $row->serial_number ?? '-' }}</td>
                    <td>{{ $row->department ?? 'Missing Data' }}</td>
                    <td>{{ $row->installation_date ?? '-' }}</td>
                    <td>
                        @php
                        $payloadRow = [
                            'id' => $row->id,
                            'installbase_code' => $row->installbase_code,
                            'brand' => optional(optional($row->product)->brand)->name,
                            'model_type' => optional($row->product)->model_type,
                            'category' => optional(optional($row->product)->category)->name,
                            'serial_number' => $row->serial_number,
                            'department' => $row->department,
                            'installation_date' => $row->installation_date,
                            'hospital' => optional($row->hospital)->name,
                            'hospital_id' => optional($row->hospital)->id,
                            'city' => optional($row->hospital)->city,
                            'pic_to_recall' => $row->pic_to_recall,
                            'department_phone' => $row->department_phone,
                            'province' => optional($row->hospital)->province?->name,
                            'installbase_status' => $row->installbase_status,
                            'end_of_warranty' => $row->end_of_warranty,
                        ];
                        @endphp
                    <button type="button"
                            class="btn btn-sm btn-primary js-show-missing"
                            data-toggle="modal"
                            data-target="#missingDetailModal"
                            data-missing='@json($row->missingCol)'
                             data-row='@json($payloadRow)'>
                        Show
                    </button>
                    </td>
                    <td>
                      <a href="#"
                         class="btn btn-sm btn-info">
                        Edit
                      </a>
                      <a href="#"
                         class="btn btn-sm btn-warning">
                        Add to Task
                      </a>
                    </td>
                  </tr>
                @endforeach


              </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>

  @endforeach

</div>


</div>

@include('modal._ib_missing_detail')

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2/vendor/sweetalert/sweetalert.all.js') }}"></script>
<script type="text/javascript">
</script>
@include('modal.modalJS._ib_missing_detail_js')
<script>
(function () {
  var el = document.getElementById('tableDragWrap');
  if (!el) return;

  var isDown = false;
  var startX = 0;
  var scrollLeft = 0;

  el.addEventListener('mousedown', function (e) {
    isDown = true;
    el.style.cursor = 'grabbing';
    startX = e.pageX - el.offsetLeft;
    scrollLeft = el.scrollLeft;
  });

  el.addEventListener('mouseleave', function () {
    isDown = false;
    el.style.cursor = 'grab';
  });

  el.addEventListener('mouseup', function () {
    isDown = false;
    el.style.cursor = 'grab';
  });

  el.addEventListener('mousemove', function (e) {
    if (!isDown) return;
    e.preventDefault();
    var x = e.pageX - el.offsetLeft;
    var walk = (x - startX) * 1.2; // speed
    el.scrollLeft = scrollLeft - walk;
  });

  // Touch support (mobile)
  el.addEventListener('touchstart', function (e) {
    startX = e.touches[0].pageX;
    scrollLeft = el.scrollLeft;
  }, { passive: true });

  el.addEventListener('touchmove', function (e) {
    var x = e.touches[0].pageX;
    var walk = (x - startX) * 1.2;
    el.scrollLeft = scrollLeft - walk;
  }, { passive: true });
})();
</script>
    // Create



@endpush
