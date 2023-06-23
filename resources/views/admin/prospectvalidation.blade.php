@extends('layout.backend.app',[
    'title' => 'Prospect',
    'pageTitle' =>'Prospect',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>Creator/ Submit Date</th>
                          
                            
                            <th>Province/ AM</th>
                            <th>Sumber Info</th>
                            <th>PIC</th>
                            <th>Rumah Sakit/ Departemen</th>
                           
                           
                            <th>Produk yang ditawarkan</th>
                            <th>Harga + PPn(IDR)</th>
                            <th>Qty</th>
                            <th>Anggaran</th>
                           
                          
                            <th>Eta PO Date</th>
                            <th>Status</th>

                          
                            <th>Table Action</th>
                            
                            
                         
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>

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
<div class="modal fade" id="validasi-modal" tabindex="-1" role="dialog" aria-labelledby="validasi-modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="validasi-modalLabel">Validasi Prospect</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="validationForm">
        <div class="form-group">
            <label for="province">Provinsi</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input readonly type="" required="" id="province" name="province" class="form-control">
        </div>
        <div class="form-group">
            <label for="submitdate">Tanggal Input</label>
            <input readonly type="" required="" id="submitdate" name="submitdate" class="form-control">
        </div>
        <div class="form-group">
            <label for="hospital">Rumah Sakit</label>
            <input readonly type="" required="" id="hospital" name="hospital" class="form-control">
        </div>
        <div class="form-group">
            <label for="department">Departemen</label>
            <input readonly type="" required="" id="department" name="department" class="form-control">
        </div>
        <div class="form-group">
            <label for="product">Produk</label>
            <input readonly type="" required="" id="product" name="product" class="form-control">
        </div>
        <div class="form-group">
            <label for="qty">Quantity</label>
            <input readonly type="" required="" id="qty" name="qty" class="form-control">
        </div>
        <div class="form-group">
            <label for="validation">Prospect Validation Status</label>
            <select required=""name="validation" id="validation" class="form-control">
             
              <option value="1">VALID</option> 
              <option value="99">EXPIRED</option> 
              <option value="404">REJECT</option> 
              <option value="0">NEW</option> 
          </select>
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
        ajax: {
          url:"{{ route('data.prospect') }}",
          type:"POST",
          data: function (d) {
              d.status=0 ;
           }
      },
        
        columns: [
          {data: 'submitdate' , name: 'submitdate'},
          {data: 'province' , name: 'province'},
          {data: 'prospect_source' , name: 'prospect_source'},
            {data: 'personInCharge' , name: 'personInCharge'},
            {data: 'hospital' , name: 'hospital'},
            //{data: 'department' , name: 'department'},
            //{data: 'unit' , name: 'unit'},
     
            {data: 'namaprod' , name: 'namaprod'},
        
            {data: 'price' , name: 'price'},
            {data: 'qty' , name: 'qty'},
            //{data: 'value' , name: 'value'},
            //{data: 'promotion' , name: 'promotion'},
            {data: 'anggaran' , name: 'anggaran'},
            {data: 'etadate' , name: 'etadate'},
           //{data: 'temperature' , name: 'temperature'},
            {data: 'statsname' , name: 'statsname'},
   
            
            
           
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
    $('body').on("click",".btn-validasi",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/prospectvalidation/"+id+"/validation",
            method: "GET",
            success:function(response){
                $("#validasi-modal").modal("show");
                $("#id").val(response.id);
                $("#province").val(response.province.name);
                var submit= response.created_at.substr(0,10);
                $("#submitdate").val(submit);
                $("#hospital").val(response.hospital.name);
                $("#department").val(response.department.name);
                $("#product").val(response.config.name);
                $("#qty").val(response.qty);
                $("#validation").val(response.status).prop("selected",true);
                
            }
        })
    });

    $("#validationForm").on("submit",function(e){
        e.preventDefault()
        var id = $("#id").val()

        $.ajax({
            url: "/admin/prospectvalidation/"+id,
            method: "PATCH",
            data: $(this).serialize(),
            success:function(){
                $('.data-table').DataTable().ajax.reload();
                $("#validasi-modal").modal("hide")
                flash("success","Data berhasil divalidasi")
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