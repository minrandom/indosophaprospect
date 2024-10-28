@extends('layout.backend.app',[
'title' => 'Prospect Detail',
'pageTitle' =>'Prospect Detail',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endpush

@section('content')



<!-- Tasks Card Example -->
<nav aria-label="...">
  <ul class="pagination justify-content-between">

    

  </ul>
</nav>

<div class="inner-container fixed-card">
  <div class="row ">
    <div id="productData" data-url=></div>

      <!-- Pending Requests Card Example -->
      <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Creator</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['creator'] }}</div></br>

              <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                Created At</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ \Carbon\Carbon::parse($allp[0]->created_at)->format('d - F - Y') }}</div>

            </div>
            <div class="col-auto">
              <i class="fas fa-percent fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>




    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-info shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Rumah Sakit</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800" id="RSPROV">{{ $coredata['hospital'] }}</div></br>
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                Departemen</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['department'] }}</div>
              </br>
              <a href="javascript:void(0)" id="' . $prospect->id . '" class="btn aksi btnaksi btn-primary btn-sm ml-2  btn-editrs">Edit RS</a>
            </div>
            <div class="col-auto">
              <i class="fas fa-hospital-user fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>






    <!-- Earnings (Annual) Card Example   //number_format($prp->config->price_include_ppn, 0, ',-', '.')-->
    <div class="col-xl-3 col-md-6 mb-4">
      <div class="card border-left-success shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Business Unit</div>
              <div class="h6 mb-0 font-weight-bold text-gray-800"> {{ $coredata['unit'] }}</div>
              <br>
              <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                Category</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $coredata['category'] }} </div>
             

            </div>
            <div class="col-auto">
              <i class="fas fa-money-bill-wave fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>


    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4 " id="nopros">
      <div class="card border-left-primary shadow h-100 py-2">
        <div class="card-body">
          <div class="row no-gutters align-items-center">
            <div class="col mr-2">
              <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">

                Nomor Prospect</div>
              <div class="h5 mb-0 font-weight-bold text-gray-800">Approve Prospect Untuk Mendapatkan Nomor Prospect</div></br>
            
            </div>
            <div class="col-auto">
              <i class="fas fa-file-alt fa-2x text-gray-500"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
 

    


  </div>
  <div class="notify" id="thealert"></div>
  <div class="row">
 
  </div>
</div>


<div class="outer-container">
  <div class="row">
  <div class="col-lg-12">
  <div id="accordion">
  <div class="card">
    <div class="card-header" id="headingOne">
      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" role='button' data-target="#collapseQ1" aria-expanded="true" aria-controls="collapseQ1">
          Prospect Quarter 1
        </button>
      </h5>
    </div>

    <div id="collapseQ1" class="collapse multi-collapse"   aria-labelledby="headingOne" >
      <div class="card w-100">
            <div class="card-body p-0">
            
                {!! $showdata[0] !!}
            </div>
            <div class="card-body ">
        <div class="d-flex justify-content-center align-items-center w-100">
          {!! $actiondata[0]!!}
        </br>
        </div>
      </div>

      </div>

    </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingTwo">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ2" aria-expanded="false" aria-controls="collapseQ2">
        Prospect Quarter 2
        </button>
      </h5>
    </div>
    <div id="collapseQ2" class="collapse multi-collapse" aria-labelledby="headingTwo" >
      <div class="card-body">
      {!! $showdata[1] !!}   
    </div>
    <div class="card-body ">
    <div class="d-flex justify-content-center align-items-center w-100">
    {!! $actiondata[1]!!}
    </br>
</div>
    </div>
  </div>
  </div>
  <div class="card">
    <div class="card-header" id="headingThree">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ3" aria-expanded="false" aria-controls="collapseQ3">
        Prospect Quarter 3
        </button>
      </h5>
    </div>
    <div id="collapseQ3" class="collapse multi-collapse" aria-labelledby="headingThree" >
      <div class="card-body">
      {!! $showdata[2] !!}  
    </div>
    <div class="card-body ">
    <div class="d-flex justify-content-center align-items-center w-100">
    {!! $actiondata[2]!!}
    </br>
  </div>
  </div>
  </div>
    </div>
 
  <div class="card">
    <div class="card-header" id="headingFour">
      <h5 class="mb-0">
        <button class="btn btn-link collapsed" role='button'  data-toggle="collapse" data-target="#collapseQ4" aria-expanded="false" aria-controls="collapseQ4">
        Prospect Quarter 4
        </button>
      </h5>
    </div>
    <div id="collapseQ4" class="collapse multi-collapse" aria-labelledby="headingFour" >
      <div class="card-body">
      {!! $showdata[3] !!}
    </div>
    <div class="card-body ">
    <div class="d-flex justify-content-center align-items-center w-100">
    {!! $actiondata[3]!!}
    </br>
  </div>
      </div>
    </div>
  </div>


</div>
  </div>
  </div>
</div>  



  <!-- Modal Edit -->
  <div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="edit-modalLabel">Edit Product Consumables</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <div class="form-group">
              <label for="Quarter">Quarter :</label>
              <input readonly type="" required="" id="quarter" name="quarter" class="form-control">
              <input type="hidden" required="" id="data" name="data" class="form-control">
            </div>
            <div class="form-group">
              <label for="bunit">Bussiness Unit :</label>
              <input readonly type="" required="" id="bunit" name="bunit" class="form-control">
              <input type="hidden" required="" id="data" name="data" class="form-control">
            </div>
            <div class="form-group">
                <div id="productInputs">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Product</div>
                    </div>
                    <select required name="product_id[]" class="form-control cr8product" >
                        <option value="">- Pilih Produk -</option>
                        <!-- Populate this select with products -->
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Qty</div>
                    </div>
                    <input type="number" required name="qty[]" min="0" class="form-control" placeholder="Quantity" value="0">
                    <div class="input-group-append">
                        <button class="btn btn-success add-product" type="button" >+</button>
                    </div>
                </div>
                </div>
            </div>

            




        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-update1">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Edit -->

  <!-- Modal Edit RS-->
  <div class="modal fade" id="editrs-modal" tabindex="-1" role="dialog" aria-labelledby="editrs-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editrs-modalLabel">Edit Info RS Prospect</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="editrsForm">
            <div class="form-group">
              <label for="RS">Rumah Sakit :</label>
             
              <select id="RS" name="RS" class="form-control" required="">

              </select>
              <input type="hidden" required="" id="datars" name="datars" class="form-control">
            </div>
            <div class="form-group">
              <label for="dept">Department :</label>
              <select id="dept" name="dept" class="form-control" required="">

              </select>
              
            </div>
 

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-updaters">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Edit RS-->
  


  <!-- Modal Produk -->
  <div class="modal fade" id="produk-modal" tabindex="-1" role="dialog" aria-labelledby="produk-modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="Produk-modalLabel">Edit Info Product</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form id="produkForm">

            <div class="form-group">
              <label for="editunit">Business Unit</label>
              <select required="" id="editunit" name="editunit" class="form-control" readonly>
              </select>
            </div>
            <div class="form-group">
              <label for="editcategory">Category</label>
              <select required="" id="editcategory" name="editcategory" class="form-control">
              </select>
            </div>
            <div class="form-group">
              <label for="productlist">Produk</label>
              <select id="productlist" name="productlist" class="form-control" required="">

              </select>
            </div>
            <div class="form-group">
              <label for="qtyitem">Quantity</label>
              <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control">
              <input type="hidden" required="" id="data" name="data" class="form-control">

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
  <!-- Modal Produk -->
  





@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>


<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    // Retrieve the alert message from local storage
    
    var conspros = @json($showdata);
    console.log(conspros);
    var core = @json($coredata);
    var cek = @json($allp);
    console.log(core);
    console.log(cek);
    

    $('body').on("click", ".btn-editrs", function() {
      var dataid = core.dataid;
      console.log(dataid)
      $.ajax({
        url: "{{ route('admin.consprospectedit', ['consumablesProspect' => ':id']) }}".replace(':id', dataid),
        method: "GET",
        success: function(response) {
          $("#editrs-modal").modal("show");
          var rsselect =$('#RS');
          CatPopulateSelect(rsselect, response.hosopt, response.prospect.hospital_id, {width: '100%'});
          $("#datars").val(response.prospect.id);
          var deptselect = $("#dept");
          CatPopulateSelect(deptselect, response.depopt, response.prospect.department_id, {width: '100%'});
          



        }
      
      
      })
    })

    $('.btn-updaters').on('click', function(e) {
        e.preventDefault()
        var prospect = $("#datars").val()
        var formData = $("#editrsForm").serialize();
        console.log(formData);
      $.ajax({
          url: "{{ route('admin.consprospectupdaters', ['consumablesProspect' => ':prospect']) }}".replace(':prospect', prospect),
          method: "PATCH",
          data: formData,
       success: function() {
            Swal.fire({
                      icon: 'success',
                      title: 'Success',
                      text: 'Data successfully updated!',
                      confirmButtonText: 'OK'
                  }).then((result) => {
                      if (result.isConfirmed) {
                          // Hide the modal
                          $('#edit-modal').modal('hide');
                          // Optionally reload the page or perform other actions
                          location.reload(); // Uncomment if you want to reload the page
                      }
                  });
                    },
    //   error: function(jqXHR, textStatus, errorThrown) {
    //     Swal.fire({
    //             icon: 'error',
    //             title: 'Error',
    //             text: 'An error occurred while updating the data. Please try again.',
    //             confirmButtonText: 'OK'
    //         });
    //         console.log('AJAX Error:', textStatus, errorThrown);
        


    // }
  });


});

  
  


    $('body').on("click", ".btn-edit", function() {
      var id = $(this).attr("id")
      $.ajax({
        url: "{{ route('admin.consprospectedit', ['consumablesProspect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {
          $("#edit-modal").modal("show");
          $("#quarter").val(response.prospect.po_target);
          $("#bunit").val(response.prospect.unit.name);
          $("#data").val(response.prospect.id);
          $('#productInputs').empty();
          var $productInputs = $('#productInputs');
          var productIds = response.prospect.config_id.split(",");
          var qtydata = response.prospect.qty.split(",").map(Number);
          var productOptions = response.configlist;
          console.log(productIds[0]);

          productIds.forEach(function(productId, index) {
                var productInputHtml = `
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Product</div>
                        </div>
                        <select required name="product_id[]" class="form-control cr8product">
                            <option value="">- Pilih Produk -</option>
                            ${productOptions.map(function(product) {
                                return `<option value="${product.id}" ${product.id == productId ? 'selected' : ''}>${product.name}</option>`;
                            }).join('')}
                        </select>
                        <input type="number" required name="qty[]" min="0" class="form-control" placeholder="Quantity" value="${qtydata[index]}">
                        <div class="input-group-append">
                            ${index === 0 ? 
                                '<button class="btn btn-success add-product" type="button">+</button>' : 
                                '<button class="btn btn-danger remove-product" type="button">-</button>'}
                        </div>
                    </div>
                `;
                $('#productInputs').append(productInputHtml);

                // Set the selected product (you need to have the product data available)
            });


            $(document).on('click', '.add-product', function() {
                var newProductHtml = `
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Product</div>
                        </div>
                        <select required name="product_id[]" class="form-control cr8product">
                            <option value="">- Pilih Produk -</option>
                            ${productOptions.map(function(product) {
                                return `<option value="${product.id}">${product.name}</option>`;
                            }).join('')}
                        </select>
                        <input type="number" required name="qty[]" min="0" class="form-control" placeholder="Quantity" value="0">
                        <div class="input-group-append">
                            <button class="btn btn-danger remove-product" type="button">-</button>
                        </div>
                    </div>
                `;
                $('#productInputs').append(newProductHtml);
            });

            $(document).on('click', '.remove-product', function() {
                $(this).closest('.input-group').remove();
            });
        }

    });

  });

 $('#edit-modal').on('hidden.bs.modal', function () {
    location.reload(); // Refresh the page
 });

$('.btn-update1').on('click', function(e) {
  e.preventDefault()
  var prospect = $("#data").val()
  var formData = $("#editForm").serialize();
  console.log(formData);
$.ajax({
    url: "{{ route('admin.consprospectupdate', ['consumablesProspect' => ':prospect']) }}".replace(':prospect', prospect),
    method: "PATCH",
    data: formData,
    success: function() {
      Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Data successfully updated!',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Hide the modal
                    $('#edit-modal').modal('hide');
                    // Optionally reload the page or perform other actions
                    location.reload(); // Uncomment if you want to reload the page
                }
            });
              },
      error: function(jqXHR, textStatus, errorThrown) {
        Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while updating the data. Please try again.',
                confirmButtonText: 'OK'
            });
            console.log('AJAX Error:', textStatus, errorThrown);
        


    }
  });


});




  
  });
</script>
@endpush