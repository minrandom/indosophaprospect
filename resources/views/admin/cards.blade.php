@extends('layout.backend.app',[
	'title' => 'Cards',
	'pageTitle' => 'Cards',
])
@section('content')
<div class="row">

    <!-- Earnings (Monthly) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Earnings (Monthly)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$40,000</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings (Annual) Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Earnings (Annual)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$215,000</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Tasks
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">50%</div>
                            </div>
                            <div class="col">
                                <div class="progress progress-sm mr-2">
                                    <div class="progress-bar bg-info" role="progressbar"
                                        style="width: 50%" aria-valuenow="50" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending Requests Card Example -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Pending Requests</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">

    <div class="col-lg-6">

        <!-- Default Card Example -->
        <div class="card mb-4">
            <div class="card-header">
                Default Card Example
            </div>
            <div class="card-body">
                This card uses Bootstrap's default styling with no utility classes added. Global
                styles are the only things modifying the look and feel of this default card example.
            </div>
        </div>

        <!-- Basic Card Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Basic Card Example</h6>
            </div>
            <div class="card-body">
                The styling for this basic card example is created by using default Bootstrap
                utility classes. By using utility classes, the style of the card component can be
                easily modified with no need for any custom CSS!
            </div>
        </div>

    </div>

    <div class="col-lg-6">

        <!-- Dropdown Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div
                class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Dropdown Card Example</h6>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">
                Dropdown menus can be placed in the card header in order to extend the functionality
                of a basic card. In this dropdown card example, the Font Awesome vertical ellipsis
                icon in the card header can be clicked on in order to toggle a dropdown menu.
            </div>
        </div>

        <!-- Collapsable Card Example -->
        <div class="card shadow mb-4">
            <!-- Card Header - Accordion -->
            <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                role="button" aria-expanded="true" aria-controls="collapseCardExample">
                <h6 class="m-0 font-weight-bold text-primary">Collapsable Card Example</h6>
            </a>
            <!-- Card Content - Collapse -->
            <div class="collapse show" id="collapseCardExample">
                <div class="card-body">
                    This is a collapsable card example using Bootstrap's built in collapse
                    functionality. <strong>Click on the card header</strong> to see the card body
                    collapse and expand!
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
            <label for="creatorname">Created By</label>
            <input readonly type="" required="" id="creatorname" name="creatorname" class="form-control">
          </div>

          <div class="form-group">
          <label for="sourceedit">Sumber Info</label>
          <input type="" placeholder="Input Nama Event Disini" style="display: none;" id="namaevent" name="namaevent" class="form-control">
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="sourceedit" name="sourceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            
          </select>
          </div>
          <div class="form-group">
          <label for="provinceedit">Provinsi</label>
            
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
            <label for="qtyitem">Quantity</label>
            <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control">
        </div>

        <div class="form-group">
            <label for="anggaranedit">Anggaran</label>
            <select type="" required="" id="anggaranedit" name="anggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>
          
        <div class="form-group">
            <label for="jenisanggaranedit">JenisAnggaran</label>
            <select  required="" id="jenisanggaranedit" name="jenisanggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}"  onchange="this.size=1";>
            </select>
          </div>
        <div class="form-group">
            <label for="etapodateedit">ETA PO DATE</label>
            <input type="date" required="" id="etapodateedit" name="etapodateedit" class="form-control" >
            
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




@stop

@push('js')

<script type="text/javascript">
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

                $("#etapodateedit").val(response.prospect.eta_po_date);
                

                $("#sourceedit").change(function() {
                var selectedOption = $(this).val();
                  if (selectedOption === "Event") {
                  $("#namaevent").show();
                  
                  } else {
                    $("#namaevent").hide();
                    }
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
</script>




@endpush