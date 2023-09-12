@extends('layout.backend.app',[
    'title' => 'Prospect Edit Detail',
    'pageTitle' =>'Prospect Edit Detail',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="row">
  <div class="col-lg-6">
    <div class="card mb-4">
       <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Prospect Detail</h6>
        </div>
        <div class="card-body">
          <div class="form-group row">
            <label for="prospectCode" class="col-sm-3 col-form-label font-weight-bold">No Prospect :</label>
            
              <div class="col-sm-8">
                 <input type="text" readonly class="form-control-plaintext" id="prospectCode" value="{{$prospect->prospect_no}}">
              </div>
              <label for="creator" class="col-sm-3 col-form-label font-weight-bold">Created By :</label>
              <div class="col-sm-8">
                  <input type="text" readonly class="form-control-plaintext" id="creator" value="{{$prospect->creator->name}}">
              </div>

              <label for="Province" class="col-sm-3 col-form-label font-weight-bold">Province :</label>

                <div class="col-sm-8">
                  <select class="form-control form-control-solid" id="province">
                      <option value="{{$prospect->province->id}}">{{$prospect->province->name}}({{$prospect->province->prov_order_no}})</option>
                      @foreach ($provOpt as $option)
                      <option value="{{ $option->id }}">{{ $option->name }}({{$option->prov_order_no}})</option>
                      @endforeach
                  </select>

          
                </div>

             
                

                <label for="PIC" class="col-sm-3 col-form-label font-weight-bold">PIC :</label>

                <div class="col-sm-8">
                  <select class="form-control form-control-solid" id="PIC">
                      <option value="{{$prospect->personInCharge->id}}">{{$prospect->personInCharge->name}}</option>
                      @foreach ($provOpt as $option)
                      <option value="{{ $option->id }}">{{ $option->name }}</option>
                      @endforeach
                  </select>

          
                </div>

             

                <label for="Hospital" class="col-sm-3 col-form-label font-weight-bold">Hospital :</label>

                <div class="col-sm-4">
                  <select class="form-control form-control-solid" id="hospital">
                      <option value="{{$prospect->hospital->id}}">{{$prospect->hospital->name}}</option>
                      @foreach ($provOpt as $option)
                      <option value="{{ $option->id }}">{{ $option->name }}</option>
                      @endforeach
                  </select>

          
                </div>

                <div class="col-sm-4">
                  <input type="text" readonly class="form-control-plaintext" id="creator" value="{{$prospect->hospital->city}}">
              </div>



                  <label for="Department" class="col-sm-3 col-form-label font-weight-bold">Department</label>

                <div class="col-sm-8">
                  <select class="form-control form-control-solid" id="hospital">
                      <option value="{{$prospect->department->id}}">{{$prospect->department->name}}</option>
                      @foreach ($provOpt as $option)
                      <option value="{{ $option->id }}">{{ $option->name }}</option>
                      @endforeach
                  </select>

          
                </div>



          </div>
   
        </div>
    </div>

         
 
          
  </div>
  <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Prospect Review</h6>
            </div>
            <div class="card-body">
                AREA PROSPECT REVIEW.....
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">History Review</h6>
            </div>
            <div class="card-body">
                AREA History REVIEW.....
            </div>
        </div>
  </div>



  <div class="col-lg-6">
        <div class="card mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Prospect ITEM</h6>
            </div>
            @foreach ($prospect->config as $configs)
            <div class="card-body">
       

            
                <label for="Product" class="col-sm-3 col-form-label font-weight-bold">Product {{$configs->id}}</label>

  <div class="col-sm-8">
  <select class="form-control form-control-solid" id="hospital">
      <option value="{{$configs->id}}">{{$configs->name}}</option>
     </select>
  </div>


    <label for="Config Code" class="col-sm-3 col-form-label font-weight-bold">Config Code</label>
    <div class="col-sm-8">
      <input type="text" readonly class="form-control-plaintext" id="creator" value="{{$configs->config_code}}">
    </div>

@endforeach


            </div>
        </div>
  </div>
  
  <div class="col-lg-6">
       
  </div>



</div>

<!--{{$prospect;}}{{$provOpt}}-->
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




</script>
@endpush