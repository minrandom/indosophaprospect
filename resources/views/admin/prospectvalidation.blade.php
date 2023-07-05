@extends('layout.backend.app',[
    'title' => 'Prospect Validation',
    'pageTitle' =>'Prospect Validation',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <a href="javascript:void(0)" id="{{ Auth::user()->name }}" class="btn btn-primary btn-sm ml-2 btn-cr8">Tambah Data</a>
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
            <label for="submitdate">Tanggal Input</label>
           <input readonly type="" required="" id="createddate" name="createddate" class="form-control" value="{{ date('M/d/Y') }}" >
          </div>

          <div class="form-group">
            <label for="Creator">Created By</label>
            <input type="hidden" required="" id="creatorid" name="creatorid" class="form-control" value="{{Auth::user()->id}}">
            <input readonly type="" required="" id="thecreators" name="thecreators" value="{{ Auth::user()->name }}" class="form-control">
          </div>
          
          <div class="form-group">
          <label for="Sumber Info">Sumber Info</label>
          <input type="" placeholder="Input Nama Event Disini" style="display: none;" required="false" id="eventname" name="eventname" class="form-control">
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="cr8source" name="cr8source" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            <option value="">-Pilih Sumber Info-</option> 
              <option value="Request From Customer">Request From Customer</option> 
              <option value="Promotion Plan By Bu">Promotion Plan By Bu</option> 
              <option value="Promotion Plan By Sales Team">Promotion Plan By Sales Team</option> 
              <option value="Event">Event</option>   
          </select>

          </div>
          <div class="form-group">
            <label for="province">Provinsi</label>
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="cr8province" name="cr8province" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1"; >
            </select>
        </div>

        <div class="form-group">
            <label for="hospital">Rumah Sakit</label>
            <input list="listhospital" autocomplete="off" type="" required="" id="cr8hospital" name="cr8hospital" class="form-control">
            <datalist id="listhospital" name="listhospital" >

            </datalist>
          </div>
        <div class="form-group">
            <label for="department">Departement</label>
            <select required="" id="cr8department" name="cr8department" class="form-control">
            </select>
        </div>
        <div class="form-group">
            <label for="product">Produk</label>
            <input list="listproduct" autocomplete="off" type="" required="" id="cr8product" name="cr8product" class="form-control">
            <datalist id="listproduct" name="listproduct" >
            </datalist>
        </div>

        <!--
        <div class="form-group">
            <label for="detail">Detail Produk</label>
            <input readonly type="" required="" id="Category" name="Category" class="form-control">
            <input readonly type="" required="" id="Brand" name="Brand" class="form-control">
            <input readonly type="" required="" id="Harga" name="Harga" class="form-control">
            <input readonly type="" required="" id="KodeConfig" name="KodeConfig" class="form-control">
            <input readonly type="" required="" id="Bussiness Unit" name="Bussiness Unit" class="form-control">
        </div>
-->
        <div class="form-group">
            <label for="quantity">Quantity</label>
            <input type="number" required="" id="qtyinput" name="qtyinput" class="form-control">
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

<!-- Modal Validation -->
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
              <label for="submitdate">Tanggal Input</label>
              <input readonly type="" required="" id="submitdate" name="submitdate" class="form-control">
          </div>
          <div class="form-group">
              <label for="creator">Created By</label>
              <input readonly type="" required="" id="creator" name="creator" class="form-control">
          </div>
        <div class="form-group">
            <label for="province">Provinsi</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input type="hidden" required="" id="validator" name="validator" class="" value="{{ Auth::user()->id}}" >
            <input type="hidden" required="" id="provcode" name="provcode" class="" >
            <input readonly type="" required="" id="provinces" name="provinces" class="form-control">
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

     
        <div class="form-group" style="display: none;" id="PIC">
            <label for="personincharge">PIC</label>
            <select required="" id="personincharge" name="personincharge" class="form-control">

            </select>
        </div>
     
        <div class="form-group">
            <label for="quan">Quantity</label>
            <input readonly type="" required="false" id="quan" name="quan" class="form-control">
        </div>



        <div class="form-group">
            <label for="validation">Prospect Validation Status</label>
         
            <select required=""name="validation" id="validation" class="form-control">
            <option value="">-Pilih Status Validasi-</option> 
              <option value="1">VALID</option> 
              <option value="99">EXPIRED</option> 
              <option value="404">REJECT</option> 
              <option value="0">NEW</option> 
          </select>
      
          <label for="infoinput" id="infoinput" style="display: none;" class="label label-warning"><b>Silahkan Input PIC</b></label>

        </div>
</br>
</br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Validation -->

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
            <label for="submitdate">Tanggal Input</label>
            <input  type="hidden" required="" id="data" name="data" class="form-control">
            
            <input readonly type="date" required="" id="submiteddate" name="submiteddate" class="form-control">
          </div>
          
          <div class="form-group">
            <label for="Creator">Created By</label>
            <input readonly type="" required="" id="creatorname" name="creatorname" class="form-control">
          </div>

          <div class="form-group">
          <label for="Sumber Info">Sumber Info</label>
          <input type="" placeholder="Input Nama Event Disini" style="display: none;" required="false" id="namaevent" name="namaevent" class="form-control">
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="sourceedit" name="sourceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            
          </select>
          </div>
          <div class="form-group">
          <label for="province">Provinsi</label>
            
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="provinceedit" name="provinceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1"; >

            </select>
        </div>
        <div class="form-group">
            <label for="hospital">Rumah Sakit</label>
            <input list="hospitallist" autocomplete="off" type="" required="" id="hospitalname" name="hospitalname" class="form-control">
            <datalist id="hospitallist" name="hospitallist" >

            </datalist>
          </div>
        <div class="form-group">
            <label for="department">Departement</label>
            <select required="" id="departmentname" name="departmentname" class="form-control">
            </select>
        </div>
        <div class="form-group">
            <label for="product">Produk</label>
            <input list="productlist" autocomplete="off" type="" required="" id="productname" name="productname" class="form-control">
            <datalist id="productlist" name="productlist" >

            </datalist>
        </div>
        <div class="form-group">
            <label for="qty">Quantity</label>
            <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control">
        </div>
        <div class="form-group">
            <label for="anggaran">Anggaran</label>
            <select type="" required="" id="anggaranedit" name="anggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>
        <div class="form-group">
            <label for="Jenis Anggaran">JenisAnggaran</label>
            <select  required="" id="jenisanggaranedit" name="jenisanggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
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


  $('body').on("click",".btn-cr8",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/prospectcreate/",
            method: "GET",
            success:function(response){
                $("#create-modal").modal("show")
                //$("#thecreators").val(id)
                var provinceSelect = $("#cr8province");
                provinceSelect.empty(); // Clear existing options
                // Populate dropdown options
                response.province.forEach(function (province) {
                  var option = $("<option>").val(province.id).text(province.name);
                  provinceSelect.append(option);
                });

                //$("#cr8hospital").val(response.prospect.hospital.name);
                var hosinput=$("#cr8hospital");
                var hospitalSelect = $("#listhospital");
                  //provinceSelect.empty(); // Clear existing options
                          
                function fetchHospitals2(provinceId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "/admin/hospital/" + provinceId+"/hospital",
                    method: "GET",
                    success: function (response) {
                      hospitalSelect.empty(); // Clear existing options

                      // Populate the hospital dropdown options
                      response.hosopt.forEach(function (hosop) {
                        var option = $("<option>").text(hosop.name);
                        option.data('value', hosop.id);
                        hospitalSelect.append(option);
                      });
                    }
                  });
                }
                //var initialProvinceId = response.prospect.province_id;
                ///fetchHospitals(initialProvinceId);
                provinceSelect.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  fetchHospitals2(selectedProvinceId);
                });
                
                var deptSelect = $("#cr8department");
                response.dept.forEach(function (dept) {
                  var deptOption = $("<option>").val(dept.id).text(dept.name);
                  deptSelect.append(deptOption);
                });


              
                var prodSelect = $("#listproduct");
                prodSelect.empty();
                //provinceSelect.empty(); // Clear existing options
                 // Populate dropdown options
                response.produk.forEach(function (produk) {

                  var conflist = $("<option>").val(produk.name);
                  prodSelect.append(conflist);
                });
/*
                $('#cr8product').on('change', function() {
                  var selectedProduct = $(this).val();
                  $.ajax({
                    url: "{{ route('data.proddetail')}}",
                    method: 'POST',
                    data: {
                      product: selectedProduct
                    },
                    success: function(response) {
                      // Update the input fields with the retrieved details
                      $('#Category').val(response.category);
                      $('#Brand').val(response.brand.name);
                      $('#Harga').val(response.price_include_ppn);
                      $('#KodeConfig').val(response.config_code);
                      $('#Bussiness Unit').val(response.unit.name);
                    }
                  });
                });
*/

        $("#cr8source").change(function() {
         var selectedOption = $(this).val();
          if (selectedOption === "Event") {
           $("#eventname").show();
           
          } else {
            $("#eventname").hide();
            }
          });


                
              
            }
        })
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
            url: "/admin/prospect",
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

    // Validtion & Update Validation
    $('body').on("click",".btn-validasi",function(){
        var id = $(this).attr("id")
        
        $("#validation").change(function() {
         var selectedOption = $(this).val();
          if (selectedOption === "1") {
           $("#PIC").show();
           $("#infoinput").show();
          } else {
          $("#PIC").hide();
          $("#infoinput").hide();
            }
          });



        $.ajax({
            url: "/admin/prospectvalidation/"+id+"/validation",
            method: "GET",
            success:function(response){
              $("#PIC").hide();
          $("#infoinput").hide();
                $("#validasi-modal").modal("show");
                $("#id").val(response.id);
                $("#provinces").val(response.province.name);
                $("#provcode").val(response.province.prov_order_no);
                $("#creator").val(response.creator.name);
                var submit= response.created_at.substr(0,10);
                $("#submitdate").val(submit);
                $("#hospital").val(response.hospital.name);
                $("#department").val(response.department.name);
                $("#product").val(response.config.name);
                $("#quan").val(response.qty);
                var picSelect = $("#personincharge");
                   // Populate dropdown options
                   picSelect.empty();
                response.piclist.forEach(function (pivc) {
                  var option = $("<option>").val(pivc.user_id).text(pivc.name);
                  picSelect.append(option);
                });

               
                $("#validation").val(response.status).prop("selected",true);
                
            }
        })
    });

    $("#validationForm").on("submit",function(e){
        e.preventDefault()
                
    // Display alert message to confirm submission
          if (confirm("Yakin Validasi Prospect ?")) {
                  // Proceed with form submission
                 
                  submitValid(id);
                  $("#validasi-modal").modal("hide");
                  $('.data-table').DataTable().ajax.reload();
                } else {
                  // Cancel form submission
                  $("#validasi-modal").modal("hide");
                  return false;
                }
             
      });
                //Validtion & Update Validation

    // function check is that any changes or not
   


    function submitValid(){
        var id = $("#id").val()
        var status = $("#validation").val()
        if (status == 404) {
          // Prompt the user to input the reason
          var reason = prompt("Input Alasan Mereject");
          if (reason === null) {
            // User clicked cancel, abort the submission
            return;
          }
   // Append the reason to the form data
    $("#validationForm").append('<input type="hidden" name="reason" value="' + reason + '">');
  }
        $.ajax({
            url: "/admin/prospectvalidation/"+id,
            method: "PATCH",
            data: $("#validationForm").serialize(),
            success:function(response){
                $('.data-table').DataTable().ajax.reload();
                $("#validasi-modal").modal("hide")
                flash("success",response.message)
            }
        })
      }


// Edit & Update
$('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/prospect/"+id+"/edit",
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show");
                $("#data").val(response.prospect.id);
                $("#creatorname").val(response.prospect.creator.name);
                //$("#provinceedit").val(response.prospect.province.name);
                var sourceSelect = $("#sourceedit");
                sourceSelect.empty(); 
                
                var firstOptionsource = $("<option>").val(response.prospect.prospect_source).text(response.prospect.prospect_source);
                sourceSelect.append(firstOptionsource);

                response.sourceoption.source[0].forEach(function (source) {
                  var option = $("<option>").val(source.name).text(source.name);
                  sourceSelect.append(option);
                });
                
                // Clear existing options
               
                var provinceSelect = $("#provinceedit");
               
                provinceSelect.empty(); // Clear existing options
                var firstOption = $("<option>").val(response.province_id).text(response.prospect.province.name);
                provinceSelect.append(firstOption);
                // Populate dropdown options
                response.provopt.forEach(function (provopt) {
                  var option = $("<option>").val(provopt.id).text(provopt.name);
                  provinceSelect.append(option);
                });

                provinceSelect.select2();
                
                var submit= response.prospect.created_at.substr(0,10);
                $("#submiteddate").val(submit);
                $("#hospitalname").val(response.prospect.hospital.name);
                var hosinput=$("#hospitalname");
                var hospitalSelect = $("#hospitallist");
                  //provinceSelect.empty(); // Clear existing options
                      

                var firstHospital = $("<option>").text(response.prospect.hospital.name);
                firstHospital.data('value',response.prospect.hospital_id)
                hospitalSelect.append(firstHospital);
                // Populate dropdown options
              
                

                function fetchHospitals(provinceId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "/admin/hospital/" + provinceId+"/hospital",
                    method: "GET",
                    success: function (response) {
                      hospitalSelect.empty(); // Clear existing options

                      // Populate the hospital dropdown options
                      response.hosopt.forEach(function (hosop) {
                        var option = $("<option>").text(hosop.name);
                        option.data('value', hosop.id);
                        hospitalSelect.append(option);
                      });
                    }
                  });
                }

                var initialProvinceId = response.prospect.province_id;
                fetchHospitals(initialProvinceId);

                provinceSelect.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  fetchHospitals(selectedProvinceId);
                });



                //$("#departmentname").val(response.prospect.department.name);
                var deptSelect = $("#departmentname");
                //provinceSelect.empty(); // Clear existing options
                var firstdept = $("<option>").val(response.department_id).text(response.prospect.department.name);
                deptSelect.append(firstdept);
                // Populate dropdown options
                response.depopt.forEach(function (depopt) {
                  var deptOption = $("<option>").val(depopt.id).text(depopt.name);
                  deptSelect.append(deptOption);
                });


                $("#productname").val(response.prospect.config.name);
                var prodSelect = $("#productlist");
                prodSelect.empty();
                //provinceSelect.empty(); // Clear existing options
                var firstconf = $("<option>").val(response.config_id).text(response.prospect.config.name);
                prodSelect.append(firstconf);
                // Populate dropdown options
                response.configlist.forEach(function (configlist) {

                  var conflist = $("<option>").val(configlist.name);
                  prodSelect.append(conflist);
                });




                $("#qtyitem").val(response.prospect.qty);
                
                var anggaranSelect = $("#anggaranedit");
                anggaranSelect.empty(); 
                
                var firstOptionanggaran = $("<option>").val(response.prospect.review.anggaran_status).text(response.prospect.review.anggaran_status);
                anggaranSelect.append(firstOptionanggaran);

                response.sourceoption.anggaran.review.forEach(function (anggaransts) {
                  var option = $("<option>").val(anggaransts.name).text(anggaransts.name);
                  anggaranSelect.append(option);
                });

                var anggartpSelect = $("#jenisanggaranedit");
                anggartpSelect.empty(); 
                
                var firstOptionanggarantp = $("<option>").val(response.prospect.review.jenis_anggaran).text(response.prospect.review.jenis_anggaran);
                anggartpSelect.append(firstOptionanggarantp);

                response.sourceoption.anggaran.Jenis.forEach(function (anggarantp) {
                  var option = $("<option>").val(anggarantp.name).text(anggarantp.name);
                  anggartpSelect.append(option);
                });


                
            }
        })
    });

    $("#editForm").on("submit",function(e){
        e.preventDefault()
        var prospect = $("#data").val()

        $.ajax({
            url: "/admin/prospectupdate/"+prospect,
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




    //delete

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