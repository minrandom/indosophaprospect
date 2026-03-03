@extends('layout.backend.app',[
    'title' => 'Installbase',
    'pageTitle' =>'Installbase',
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
        <a type="button" class="btn btn-primary" href="{{ route('data.ibreview') }}">
          Under Review Data
        </a>
    </div>

        <div class="card-body">
            <div class="table-responsive" id="tableDragWrap" style="cursor: grab;">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>InstallBase Code</th>
                            <th>Brand</th>
                            <th>Model & Type</th>
                            <th>Kategori</th>
                            <th>Product Description</th>
                            <th>Serial Number</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Hospital Name</th>
                            <th>Department</th>
                            <th>Location</th>
                            <th>PIC to Recall</th>
                            <th>Department Phone Number</th>
                            <th>Installation Date</th>
                            <th>Status</th>
                            <th>Maintenance Status</th>
                            <th>End of Warranty Cover</th>
                            <th>Service Contract Status</th>
                            <th>Last Review</th>
                            <th>Last Review Notes</th>
                            <th>Table Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

</div>


<!-- Modal Edit -->
{{-- <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editForm">
        <div class="form-group">
        <input type="hidden" required="" id="data" name="data" class="form-control">
            <label for="name">Nama Config</label>
            <input type="" required placeholder="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="code">Kode Config</label>
            <input type="" required  id="code" name="code" class="form-control">
        </div>
        <div class="form-group">
            <label for="unit">Business Unit</label>
            <select id="unit" name="unit" class="form-control" required="">

            </select>
        </div>

        <div class="form-group">
            <label for="category">Product Category</label>
            <select id="category" name="category" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="Jenis">Jenis</label>
            <select id="Jenis" name="Jenis" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="brand">Brand</label>
            <select id="brand" name="brand" class="form-control">

            </select>
        </div>
        <div class="form-group">
          <label for="type">Tipe</label>
          <input type="" required placeholder="Input Tipe Alat" id="type" name="type" class="form-control" >
        </div>
        <div class="form-group">
            <label for="uom">UOM</label>
            <select id="uom" name="uom" class="form-control" required="">


            </select>
        </div>
        <div class="form-group">
            <label for="consist">Consist Of</label>
            <input type="" id="consist" name="consist" class="form-control">
        </div>
        <div class="form-group">
        <label for="price">Harga</label>
            <input type="number" id="price" name="price" class="form-control">
        </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update" id="btn-update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div> --}}
<!-- Modal Edit -->

<!-- Destroy Modal -->
{{-- <div class="modal fade" id="destroy-modal" tabindex="-1" role="dialog" aria-labelledby="destroy-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="destroy-modalLabel">Yakin Hapus ?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <button type="button" class="btn btn-danger btn-destroy">Hapus</button>
      </div>
    </div>
  </div>
</div> --}}
<!-- Destroy Modal -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script type="text/javascript">

  $(function () {

    var table = $('.data-table').DataTable({
        pageLength: 5,
  lengthMenu: [5,10, 25, 50, 100],
        processing: true,
        serverSide: true,
        ajax: "{{ route('data.installbase') }}",
        columns: [
            {data: 'id' , name: 'id'},
            {data: 'installbase_code' , name: 'installbase_code'},
            {data: 'brand' , name: 'brand'},
            {data: 'product.model_type' , name: 'model_type'},
            {data: 'category' , name: 'category'},
            {data: 'product.description' , name: 'product_name'},
            {data: 'serial_number' , name: 'serial_number'},
            {data: 'province' , name: 'province'},
            {data: 'hospital.city' , name: 'city'},
            {data: 'hospital.name' , name: 'hospital'},
            {data: 'department' , name: 'department'},
            {data: 'equipment_location' , name: 'location'},
            {data: 'pic_to_recall' , name: 'pic_to_recall'},
            {data: 'department_phone' , name: 'department_phone'},
            {data: 'installation_date' , name: 'installation_date'},
            {data: 'installbase_status' , name: 'installbase_status'},
            {data: 'maintenance_status' , name: 'maintenance_status'},
            {data: 'end_of_warranty' , name: 'end_of_warranty'},
            {data: 'service_contract' , name: 'service_contract'},
            {data: 'last_review' , name: 'last_review'},
            {data: 'note_last_review' , name: 'note_last_review'},

            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });
  });



</script>
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
