@extends('layout.backend.app',[
    'title' => 'Prospect Check',
    'pageTitle' =>'CHECK YOUR CREATED PROSPECT',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endpush

@section('content')
<div class="notify"></div>

<div class="card">
       
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                          <th>No</th>
                            <th>Submit Date</th>
                            <th>Province</th>
                            <th>Sumber Info</th>
                            <th>Rumah Sakit/ Departemen</th>
                           
                           
                            <th>Produk yang ditawarkan</th>
                            <th>Harga + PPn(IDR)</th>
                            <th>Qty</th>
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

<div id="productData" data-url="{{ route('product.getProducts') }}"></div>


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
            <label for="hospitallist">Rumah Sakit</label>
            <select id="hospitallist" name="hospitallist" class="form-control" required="">
            </select>
          </div>

        <div class="form-group">
            <label for="departmentname">Departemen</label>
            <select required="" id="departmentname" name="departmentname" class="form-control">
            </select>
        </div>

        <div class="form-group">
            <label for="editunit">Business Unit</label>
            <select required="" id="editunit" name="editunit" class="form-control">
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
            <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control" min='1'oninput="validateQuantity()">
            <p id="quantityWarning" style="color: red; display: none;">Quantity Minimal angka 1</p>
 
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
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>

<script type="text/javascript">


                         

  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,
        lengthMenu: [[5, 10, 20], [5, 10, 20]],
        ajax: {
          url:"{{ route('data.createdcheck') }}",
          type:"GET",
        },
            
        columns: [
          {data: 'DT_RowIndex' , name: 'rowindex'},
         
            {data: 'submitdate' , name: 'submitdate'},
            {data: 'province.name' , name: 'province_name'},
            {data: 'prospect_source' , name: 'prospect_source'},
            {data: 'hospital' , name: 'hospital'},
            {data: 'config.name' , name: 'config_name'},
            {data: 'price' , name: 'price'},
            {data: 'qty' , name: 'qty'},
            {data: 'statsname' , name: 'statsname'},
            {data: 'action', name: 'action', orderable: false, searchable: true},
   
        ]
    });
  });
      
// Edit & Update
$('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
            method: "GET",
            success:function(response){
              
                $("#edit-modal").modal("show");
                $("#data").val(response.prospect.id);
                $("#creatorname").val(response.prospect.creator.name);
                //$("#provinceedit").val(response.prospect.province.name);
                var sourceSelect = $("#sourceedit");
                populateSelectFromDatalist('sourceedit', response.sourceoption.source,"Pilih Sumber Informasi");
                response.event.forEach(function (event) {
                  var option = $("<option>").val(event.id).text(event.name);
                  sourceSelect.append(option);
                });


                var firstOptionsource = $("<option>").val(response.prospect.prospect_source).text(response.prospect.prospect_source).attr("selected", true);
                sourceSelect.prepend(firstOptionsource);
                             
                var provinceSelect = $("#provinceedit");
               populateSelectFromDatalist('provinceedit', response.provopt,"Pilih Provinsi");
                var firstOption = $("<option>").val(response.prospect.province_id).text(response.prospect.province.name).attr("selected", true);
                provinceSelect.prepend(firstOption);
                
                
                var submit= response.prospect.created_at.substr(0,10);
                $("#submiteddate").val(submit);
                $("#hospitalname").val(response.prospect.hospital.name);
                //var hosinput=$("#hospitalname");
                var hospitalSelect = $("#hospitallist");
                  //provinceSelect.empty(); // Clear existing options
                      

               
                populateSelectFromDatalist('hospitallist', response.hosopt,"Pilih Rumah Sakit");
                var firstHospital = $("<option>").val(response.prospect.hospital_id).text(response.prospect.hospital.name).attr('selected',true);
                hospitalSelect.prepend(firstHospital);
                // Populate dropdown options
                  function fetchHospitals(provinceId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
                    method: "GET",
                    success: function (response) {

                      populateSelectFromDatalist('hospitallist', response.hosopt,"Pilih Rumah Sakit");

                    }
                  });
                }

                


               provinceSelect.on("change", function () {
                  var selectedProvinceId = $(this).val();
                  fetchHospitals(selectedProvinceId);
                 });



                //$("#departmentname").val(response.prospect.department.name);
                var deptSelect = $("#departmentname");
               populateSelectFromDatalist('departmentname', response.depopt,"Pilih Departemen");
               var firstdept = $("<option>").val(response.prospect.department_id).text(response.prospect.department.name).attr("selected", true);
                deptSelect.prepend(firstdept);


                var unitselect = $("#editunit");
               populateSelectFromDatalist('editunit', response.bunit,"Pilih Business Unit");
               var firstunit = $("<option>").val(response.prospect.unit_id).text(response.prospect.unit.name).attr("selected", true);
                unitselect.prepend(firstunit);
                
                 var catSelect=$("#editcategory");
                 var idcat=response.prospect.config.category_id;
                 populateSelectFromDatalist('editcategory', response.catopt,"Pilih Kategori Produk");

                 function catdata(unitId) {
                    // Make an AJAX call to retrieve the category name based on unitId
                    $.ajax({
                      url: "{{ route('admin.getCatname', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                      method: "GET",
                      success: function (response) {
                        var catname = response.catdat.name;
                        var catid = response.catdat.id;

                       // console.log(catname);
                        // Call the callback function with the retrieved category name
                        var ncatSelect=$("#editcategory");
                    // For example, update an input field with the category name
                         var firstCategory = $("<option>").val(catid).text(catname).attr('selected',true);
                         ncatSelect.append(firstCategory);
                      
                      },
                     
                    });
                  }
                                  
                  catdata(idcat);
                           
                function fetchcat2(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('editcategory', response.catopt,"Pilih Kategori Produk");
                    }
                  });
                }

                unitselect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat2(selectedunitId);
                });

                var prodSelect = $("#productlist");
                prodSelect.empty();
                populateSelectFromDatalist('productlist', response.configlist,"Pilih Produk");
                var firstconf = $("<option>").val(response.prospect.config_id).text(response.prospect.config.name).attr('selected',true);
                prodSelect.prepend(firstconf);

                $("#editunit, #editcategory").on("change", function () {
                  var selectedBusinessUnitId = $("#editunit").val();
                  var selectedCategoryId = $("#editcategory").val();
                  var selectformId="productlist";

                  if (selectedBusinessUnitId && selectedCategoryId) {
                    populateProductSelect(selectedBusinessUnitId, selectedCategoryId,selectformId);
                  } else {
                
                    var productSelect = $("#productlist");
                    productSelect.empty();
                    productSelect.append('<option value="">- Pilih Produk -</option>');
                  }
                });


              




                $("#qtyitem").val(response.prospect.qty);
                
                
                var anggaranSelect = $("#anggaranedit");
                anggaranSelect.empty(); 
                populateSelectFromDatalist('anggaranedit', response.sourceoption.anggaran.review,"Pilih Review Anggaran");
                var firstOptionanggaran = $("<option>").val(response.prospect.review.anggaran_status).text(response.prospect.review.anggaran_status).attr('selected',true);
                anggaranSelect.prepend(firstOptionanggaran);

                var anggartpSelect = $("#jenisanggaranedit");
                anggartpSelect.empty(); 
                populateSelectFromDatalist('jenisanggaranedit', response.sourceoption.anggaran.Jenis,"Pilih JenisAnggaran");
                var firstOptionanggarantp = $("<option>").val(response.prospect.review.jenis_anggaran).text(response.prospect.review.jenis_anggaran).attr('selected',true);
                anggartpSelect.prepend(firstOptionanggarantp);

                $("#etapodateedit").val(response.prospect.eta_po_date);
                var today = new Date();

                  // Calculate the date after 30 days from today
                  var thirtyDaysFromToday = new Date(today);
                  thirtyDaysFromToday.setDate(today.getDate() + 30);

                  var todayFormatted = today.toISOString().split('T')[0];
                  var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];
                  $("#etapodateedit").attr('min',thirtyDaysFromTodayFormatted)

              

            }
        })
    });

    $("#editForm").on("submit",function(e){
        e.preventDefault()
        var prospect = $("#data").val()

        $.ajax({
            url: "{{ route('admin.prospectupdate', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
            method: "PATCH",
            data: $(this).serialize(),
            success:function(){
                $('.data-table').DataTable().ajax.reload();
                $("#edit-modal").modal("hide");
                flash("success","Data berhasil diupdate");
                $('.notify').focus()
                
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
                $("#destroy-modal").modal("hide");
                $('.data-table').DataTable().ajax.reload();
                flash('success','Data berhasil dihapus');
                
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