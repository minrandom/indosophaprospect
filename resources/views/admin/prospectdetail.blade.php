@extends('layout.backend.app',[
    'title' => 'Prospect',
    'pageTitle' =>'Prospect',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" href="#tab1" data-toggle="tab">Tab 1</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#tab2" data-toggle="tab">Tab 2</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="#tab3" data-toggle="tab">Tab 3</a>
  </li>
</ul>

<div class="tab-content">
  <div class="tab-pane active" id="tab1">
    <!-- Content for Tab 1 -->
    <div class="card">
      <div class="card-header">
       Button trigger modal 
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
      </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table" id="tab1Table">
                    <thead>
                        <tr>
                        <th>Tanggal Submit</th>
                            <th>User Creator</th>
                          
                            
                            
                         
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>

  </div>
  <div class="tab-pane" id="tab2">
    <!-- Content for Tab 2 -->
    <div class="card">
      <div class="card-header">
       Button trigger modal 
          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
      </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table" id="tab2Table">
                    <thead>
                        <tr>
                            <th>Tanggal Submit</th>
                       
                            <th>Kategori Produk</th>
                            <th>Nama Produk</th>
                            <th>Kode Config</th>
                            <th>Harga + PPn(IDR)</th>        
                            
                            
                         
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
    </div>


  </div>
  <div class="tab-pane" id="tab3">
    <!-- Content for Tab 3 -->
  </div>
</div>

<!--
<div class="card">
    <div class="card-header">
     Button trigger modal 
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>Tanggal Submit</th>
                            <th>User Creator</th>
                            <th>Prospect No</th>
                            <th>Province + OrderNo</th>
          
                            
                            
                         
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>-->

<!-- Modal Create -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="create-modalLabel">Create Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="createForm">
        <div class="form-group">
            <label for="n">Name</label>
            <input type="" required="" id="n" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="pon">Prov Order No</label>
            <input type="" required="" id="pon" name="prov_order_no" class="form-control">
        </div>
        <div class="form-group">
            <label for="prc">Prov Region Code</label>
            <input type="" required="" id="prc" name="prov_region_code" class="form-control">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-store">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Create -->

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


                         

  


    // Reset Form
        function resetForm(){
            $("[name='name']").val("")
            $("[name='prov_order_no']").val("")
            $("[name='prov_region_code']").val("")
        }
    //

    // Create 

    $("#createForm").on("submit",function(e){
        e.preventDefault()

        $.ajax({
            url: "/admin/province",
            method: "POST",
            data: $(this).serialize(),
            success:function(){
                $("#create-modal").modal("hide")
                $('.data-table').DataTable().ajax.reload();
                flash("success","Data berhasil ditambah")
                resetForm()
            }
        })
    })

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
            url: "/admin/province/"+id,
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



    $(document).ready(function() {
  // Initialize DataTables for each tab
  $('#tab1Table').DataTable();
  $('#tab2Table').DataTable();
  $('#tab3Table').DataTable();

  // Fetch and load tab content via AJAX
  $('.nav-tabs a').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr('href');
    var table = $(target + 'Table');

    if (!table.DataTable().data().count()) {
      $.ajax({
        url: '/load-tab-data',
        type: 'GET',
        data: { tab: target },
        success: function(response) {
          table.DataTable().clear().rows.add(response).draw();
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
    }
  });
});

</script>
@endpush