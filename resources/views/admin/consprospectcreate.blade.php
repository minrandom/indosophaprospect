@extends('layout.backend.app',[
    'title' => 'Create New Consumables Prospect',
    'pageTitle' =>'Create New Consumables Prospect',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
        .tooltip-inner {
            max-width: 300px; /* Set the maximum width of the tooltip */
            width: auto; /* Allow the tooltip to expand horizontally if needed */
        }
    </style>



@endpush

@section('content')
<div class="notify"></div>

<div class="card">
 
        <div class="card-body">
           
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Create Consumables Prospect</div>

                <div class="card-body">
                    <form id="createForm">
                        @csrf

                        <div class="form-group">
            <label for="submitdate">Tanggal Input</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="thecreators">Created By</label>
            <input type="hidden" required id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input readonly type="" required id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
          </div>
          
          <div class="form-group">
          <label for="cr8source">Sumber Info</label>
          
          <select required id="cr8source" name="cr8source" class="form-control " required="" >
          </select>
          <input type="" placeholder="Input Nama Event Disini" style="display: none;" id="eventname" name="eventname" class="form-control">

          </div>

          <div class="form-group">
            <label for="cr8infoextra">Informasi Tambahan</label>
             <input required type='' placeholder='Misal : " Kebutuhan untuk ruang OK baru " , " Kebutuhan Banyak Ventilator ", dsb' id="cr8infoextra" name="cr8infoextra" class="form-control" required  data-toggle="tooltip"
           title="Misal: 'Kebutuhan untuk ruang OK baru', 'Kebutuhan Banyak Ventilator', 'Permintaan dari dr.ABC'." data-placement="top" >
          </div>
           <div class="form-group">
            <label for="cr8province">Provinsi</label>
             <select required='' id="cr8province" name="cr8province" class="form-control" >
            
          </select>
        </div>

        <div class="form-group">
            <label for="cr8hospital">Rumah Sakit</label>
             
            <select id="cr8hospital" name="cr8hospital" class="form-control" required=  >

            </select>
          </div>
        <div class="form-group">
            <label for="cr8department">Departement</label>
            <select required id="cr8department" name="cr8department" class="form-control"  onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
        </div>

        <div class="form-group">
            <label for="cr8bunit">Business Unit</label>
            <select required id="cr8bunit" name="cr8bunit" class="form-control">
            </select>
        </div>
        <div class="form-group">
            <label for="cr8category">Category</label>
            <select required id="cr8category" name="cr8category" class="form-control">
            </select>
        </div>
        
        @foreach (['Q1', 'Q2', 'Q3', 'Q4'] as $quarter)
    <hr style="height:1px;border:none;color:#333;background-color:#333;">
    <div class="form-group">
        <label for="products_{{ $quarter }}">Products for {{ $quarter }}</label>
        <div id="productInputs_{{ $quarter }}">
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">Product</div>
                </div>
                <select required name="product_id_{{ $quarter }}[]" class="form-control cr8product" data-quarter="{{ $quarter }}">
                    <option value="">- Pilih Produk -</option>
                    <!-- Populate this select with products -->
                </select>
            </div>
            <div class="input-group mb-3">
                <div class="input-group-prepend">
                    <div class="input-group-text">Qty</div>
                </div>
                <input type="number" required name="qty_{{ $quarter }}[]" min="0" class="form-control" placeholder="Quantity" value="0">
                <div class="input-group-append">
                    <button class="btn btn-success add-product" type="button" data-quarter="{{ $quarter }}">+</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

                                <hr style="height:1px;border:none;color:#333;background-color:#333;">
        <div class="form-group">
            <label for="anggarancr8">Anggaran</label>
            <select type="" required id="anggarancr8" name="anggarancr8" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>
          
        <div class="form-group">
            <label for="jenisanggarancr8">Jenis Anggaran</label>
            <select  required id="jenisanggarancr8" name="jenisanggarancr8" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>

        <!-- <div class="form-group">
            <label for="etapodatecr8">Estimasi PO Date</label>
            <input type="date" required="" id="etapodatecr8" name="etapodatecr8" class="form-control" >
           
          </div> -->
          </br>
          </br>
      </div>
      <div class="modal-footer">
        <!-- <button type="submit" class="btn btn-info btn-draft" id="btn-draft" >Simpan Draft</button> -->
        <button type="submit" class="btn btn-primary btn-store" id="btn-store" >Kirim Untuk divalidasi</button>
        </form>
      </div>
            </div>
        </div>
    </div>
</div>


          </div>

    
</div>
<div id="productData" data-url="{{ route('product.getProducts') }}"></div>
<!-- Modal Create -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
</div>
<!-- Modal Create -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script type="text/javascript">


                         

  $(function () {
 var id = $(this).attr("id")
 $('[data-toggle="tooltip"]').tooltip(); 
        $.ajax({
            url: "{{ route('admin.consprospectcreate') }}",
            method: "GET",
            success:function(response){
              
              $("#cr8hospital").val("");
                $("#cr8product").val("");
                //$("#eventname").val("");
                $("#qtyinput").val("1");
        
                var today = new Date();

              // Calculate the date after 30 days from today
              var thirtyDaysFromToday = new Date(today);
              thirtyDaysFromToday.setDate(today.getDate() + 30);

              var todayFormatted = today.toISOString().split('T')[0];
              var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];


              $('#etapodatecr8').attr('min', thirtyDaysFromTodayFormatted);

              //populate from array
              var eventSelect = $("#cr8source");
              populateSelectFromDatalist('cr8source', response.source.source,"Pilih Sumber Informasi");
              //populate from event database
              response.event.forEach(function (event) {
                  var option = $("<option>").val(event.id).text(event.name);
                  eventSelect.append(option);
                });

                var provinceSelect = $("#cr8province");
               populateSelectFromDatalist('cr8province', response.province,"Pilih Provinsi");

               var hosinput=$("#cr8hospital");
                        
                function fetchHospitals2(provinceId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
                    method: "GET",
                    success: function (response) {
                   
                        populateSelectFromDatalist('cr8hospital', response.hosopt,"Pilih Rumah Sakit");
                      
                    }
                  });
                }
               
                provinceSelect.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  fetchHospitals2(selectedProvinceId);
                });

                var deptSelect = $("#cr8department");
                populateSelectFromDatalist('cr8department', response.dept,"Pilih Departemen");

             
                var unitSelect = $("#cr8bunit");
                populateSelectFromDatalist('cr8bunit', response.bunit,"Pilih Business Unit");
                var catSelect=$("#cr8category");
                
                function fetchcat(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('cr8category', response.catopt,"Pilih Kategori Produk");
                    }
                  });
                }
             
                unitSelect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat(selectedunitId);
                });
                
                $("#cr8category").on("change", function () {
                  var selectedBusinessUnitId = $("#cr8bunit").val();
                  var selectedCategoryId = $("#cr8category").val();
                  
                  if (selectedBusinessUnitId && selectedCategoryId) {
                        populateAllConsProductSelects(selectedBusinessUnitId, selectedCategoryId);
                    } else {
                        $('.cr8product').each(function() {
                            var productSelect = $(this);
                            productSelect.empty();
                            productSelect.append('<option value="">- Pilih Produk -</option>');
                          
                        });
                    }
                });
/*
*/              var anggaranSelect = $("#anggarancr8");
                populateSelectFromDatalist('anggarancr8', response.source.anggaran.review,"Review Anggaran");

               var anggartpSelect = $("#jenisanggarancr8");
                populateSelectFromDatalist('jenisanggarancr8', response.source.anggaran.Jenis,"Pilih Jenis Anggaran");
             
                anggaranSelect.on("change", function () {
                  var anggarselecte = $(this).val();
                  if (anggarselecte === "0") {
                    var option = $("<option>").val("9").text("Belum Tahu").attr('selected',true);
                    anggartpSelect.append(option); // Set the value of anggartpSelect to 9 (or any desired value) when anggaranSelect is 0
                    anggartpSelect.prop("disabled", true); // Disable the anggartpSelect element
                  } else {
                    anggartpSelect.prop("disabled", false);
                    populateSelectFromDatalist('jenisanggarancr8', response.source.anggaran.Jenis,"Pilih Jenis Anggaran");
                  }
                });

                
                $('.add-product').click(function() {
                  var selectedBusinessUnitId = $("#cr8bunit").val();
                  var selectedCategoryId = $("#cr8category").val();
                    var quarter = $(this).data('quarter');
                    var newProductInput = `
                    <div class="prod-group">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Product</div>
                    </div>
                    <select required name="product_id_${quarter}[]" class="form-control cr8product" data-quarter="${quarter}">
                        <option value="">- Pilih Produk -</option>
                        <!-- Populate this select with products -->
                    </select>
                </div>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <div class="input-group-text">Qty</div>
                    </div>
                    <input type="number" required name="qty_${quarter}[]" min="0" class="form-control" placeholder="Quantity" value="0">
                    <div class="input-group-append">
                        <button class="btn btn-danger remove-product" type="button">-</button>
                    </div>
                </div>
            </div>`;
                  $('#productInputs_' + quarter).append(newProductInput);

                  if (selectedBusinessUnitId && selectedCategoryId) {
                   populateConsProductSelect(selectedBusinessUnitId, selectedCategoryId, $('#productInputs_' + quarter + ' .cr8product').last());
                   }

                });


                // Draft Item Populate
           



            $(document).on('click', '.remove-product', function() {
                $(this).closest('.prod-group').remove();
            });
              
            }
            
        })
    });

    


    // Create 
    function submitForm(url, successMessage) {
        var formData = $("#createForm").serialize();

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function() {
                //$("#create-modal").modal("hide");
                //$('.data-table').DataTable().ajax.reload();
                $("#createForm")[0].reset();
                flash("success", successMessage);
                document.querySelector(".notify").scrollIntoView({
                behavior: "smooth",
                block: "start"
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('AJAX Error:', textStatus, errorThrown);
                
                flash("danger", "Tolong Isi Bagian yang masih kosong!!");
                document.querySelector(".notify").scrollIntoView({
                behavior: "smooth",
                block: "start"
                });
            }
        });
    };


/*
    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        var clickedButton = $(document.activeElement);

        if (clickedButton.is('#btn-store')) {
            submitForm("{{ route('admin.prospect.store') }}", "Data berhasil dikirim untuk divalidasi");
        } else if (clickedButton.is('#btn-draft')) {
            submitForm("{{ route('admin.prospect.saveDraft') }}", "Data berhasil disimpan sebagai draft");
        }
    });

    */
  
    $('#btn-store').on('click', function(e) {
        $(this).focus();
        e.preventDefault();
       $(document.activeElement);
       submitForm("{{ route('admin.consprospect.store') }}", "Data berhasil dikirim untuk divalidasi");
      });

   $('#btn-draft').on('click', function(e) {    
         $(this).focus();
        e.preventDefault();
       $(document.activeElement);
        submitForm("{{ route('admin.prospect.saveDraft') }}", "Data berhasil disimpan sebagai draft");
        
    });
  


    //delete

    // $('body').on("click",".btn-delete",function(){
    //     var id = $(this).attr("id")
    //     $(".btn-destroy").attr("id",id)
    //     $("#destroy-modal").modal("show")
    // });

    // $(".btn-destroy").on("click",function(){
    //     var id = $(this).attr("id")

    //     $.ajax({
    //         url: "/admin/province/"+id,
    //         method: "DELETE",
    //         success:function(){
    //             $("#destroy-modal").modal("hide")
    //             $('.data-table').DataTable().ajax.reload();
    //             flash('success','Data berhasil dihapus')
    //         }
    //     });
    // })

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