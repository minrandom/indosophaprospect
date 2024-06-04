@extends('layout.backend.app',[
    'title' => 'Config',
    'pageTitle' =>'Config',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <a type="button" class="btn btn-primary" href="{{ route('admin.configcreate')}}">
          Tambah Data
        </a>
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Config</th>
                            <th>Nama</th>
                            <th>Bussiness Unit</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Brand</th>
                            <th>Tipe</th>
                            <th>UOM</th>
                            <th>Consist Of</th>
                            <th>Harga +PPn (IDR)</th>
                            <th>Action</th>
                         
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
      <div class="modal-body">
        <form id="editForm">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input type="" required="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="prov_order_no">Province Order No</label>
            <input type="" required="" id="prov_order_no" name="prov_order_no" class="form-control">
        </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Edit -->

<!-- Destroy Modal -->
<div class="modal fade" id="destroy-modal" tabindex="-1" role="dialog" aria-labelledby="destroy-modalLabel" aria-hidden="true">
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
</div>
<!-- Destroy Modal -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>

<script type="text/javascript">


                         

  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('data.config') }}",
        columns: [
            {data: 'id' , name: 'id'},
            {data: 'config_code' , name: 'config_code'},
            {data: 'name' , name: 'name'},
            {data: 'bu' , name: 'bu'},
            {data: 'category.name' , name: 'category'},
            {data: 'genre' , name: 'genre'},
            {data: 'brand.name' , name: 'brand'},
            {data: 'type' , name: 'type'},
            {data: 'uom' , name: 'uom'},
            {data: 'consist_of' , name: 'consist_of'},
            {data: 'pformat' , name: 'pformat'},
   
           
            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });
  });



    // Create

    // Edit & Update
    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/province/"+id+"/edit",
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show")
                $("#id").val(response.id)
                $("#name").val(response.name)
                $("#prov_order_no").val(response.prov_order_no)
                
            }
        })
    });

    $("#editForm").on("submit",function(e){
        e.preventDefault()
        var id = $("#id").val()

        $.ajax({
            url: "{{}}",
            method: "PATCH",
            data: $(this).serialize(),
            success:function(){
                $('.data-table').DataTable().ajax.reload();
                $("#edit-modal").modal("hide")
                flash("success","Data berhasil diupdate")
            }
        })
    })
    //Edit & Update

    $('body').on("click",".btn-delete",function(){
        var id = $(this).attr("id")
        $(".btn-destroy").attr("id",id)
        $("#destroy-modal").modal("show")
    });

    $(".btn-destroy").on("click",function(){
        var id = $(this).attr("id")

        $.ajax({
            url: "/admin/province/"+id,
            method: "DELETE",
            success:function(){
                $("#destroy-modal").modal("hide")
                $('.data-table').DataTable().ajax.reload();
                flash('success','Data berhasil dihapus')
            }
        });
    })

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