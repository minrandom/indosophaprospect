@extends('layout.backend.app',[
    'title' => 'Indosopha As Vendor',
    'pageTitle' =>'Buyer List',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <!-- <a type="button" class="btn btn-primary" href="#">
          Tambah Vendor Baru
</a> -->
        
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>Kode RS</th>
                            <th>Nama RS</th>
                            <th>Province</th>
                            <th>Department</th>
                            <th>Transaksi Terakhir</th>
                            <th>Status Vendor</th>
                            <th>Table Button</th>
                     
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>



<!-- Modal Edit -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="edit-modalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editForm">
      <div class="modal-body">
       
     
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update" id="btn-update">Update</button>
       
      </div>
      </form>
    </div>
  </div>
</div>
<!-- Modal Edit -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/regencies.js"></script>

<script type="text/javascript">

  $(function () {
   
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('databuyerList') }}",
            dataSrc: function(json) {
                console.log('Full response:', json);
                return json.data;
            }
        },
        columns: [
            {data: 'hospital.code' , name: 'code'},
            {data: 'hospital.name' , name: 'name'},
            {data: 'provname', name: 'provname'},
            {data: 'department.name', name: 'dept'},
            {data: 'last_transaction_date', name: 'latestTrans'},
            {data: 'vendStatus', name: 'vendStatus'},
       
            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });
  });

    // Reset Form
        function resetForm(){
            $("[name='name']").val("")
            $("[name='prov_order_no']").val("")
            $("[name='prov_region_code']").val("")
        }
 


    function flash(type,message){
        $(".notify").html(`<div class="alert alert-`+type+` alert-dismissible fade show" role="alert">
          `+message+`
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
          </div>`)
    }

</script>
@endpush