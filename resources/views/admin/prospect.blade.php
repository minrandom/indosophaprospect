@extends('layout.backend.app',[
    'title' => 'Prospect Review',
    'pageTitle' =>'Prospect Review',
    ])
    
    @push('css')
    <link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    
    @endpush
    
    @section('content')
    <div class="notify"></div>
    
    <div class="card">
      <div class="card-header">
        <!-- Button trigger modal -->
        <h5>FILTER</h5>
        <div class="row">
          <div class="col-4">
          <div class="col col-lg-8">
            <div class="form-group">
            <label for="sumberinfofilter">Sumber Prospect :</label>
             <select id="sumberinfofilter" name="sumberinfofilter" class="form-control dropdown" required=""  >
               
               </select>
              </div>
          </div>  
            <div class="col col-lg-8">
              <div class="form-group">
                  <label for="tempefilter">Temperature :</label>
                   <select id="tempefilter" name="tempefilter" class="form-control dropdown" required=""  >
                      <option value="1">HOT Prospect</option>
                      <option value="2">Prospect</option>
                      <option value="3">Funnel</option>
                      <option value="4">Drop</option>
                   </select>
              </div>
            </div>
            </div>
        
        <div class="col-4">
            <div class="col col-lg-8">
              <div class="form-group">
                <label for="provincefilter">Province :</label>
                <select id="provincefilter" name="provincefilter" class="form-control dropdown" required=""  >
                  
                  </select>
                </div>
              </div>
              <div class="col col-lg-8">
                <div class="form-group">
                <label for="picfilter">PIC :</label>
                <select id="picfilter" name="picfilter" class="form-control dropdown" required=""  >
            
                 </select>
              </div>
              </div>
          </div>
        
          <div class="col-4">
      <div class="col col-lg-8">
        <div class="form-group">
            <label for="BUfilter">Business Unit :</label>
             <select id="BUfilter" name="BUfilter" class="form-control dropdown" required=""  >
            
          </select>
        </div>
      </div>
      <div class="col col-lg-8">
        <div class="form-group">
            <label for="catfilter">Product Category :</label>
             <select id="catfilter" name="catfilter" class="form-control dropdown" required=""  >
            
          </select>
        </div>
      </div>

    </div>

    </div>
    
    
        <div class="card-body">
                 
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                        <th>Validasi</th>
                            <th>PIC</th>
                            <th>Prospect No</th>
                            <th>Province</th>
                            <th>Rumah Sakit + Department</th>
                            <th>Nama Produk + Qty</th>
                            <th>Value (IDR)</th>
                            <th>Tahap Promosi</th>
                            <th>Review</th>
                            <th>Anggaran</th>
                            <th>Eta PO Date</th>
                            <th>Temperature</th>
                         
                           
                            <th>Table Action</th>
                            
                            
                         
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>

@include('modal._createprospect_modal')

<div id="productData" data-url="{{ route('product.getProducts') }}"></div>


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
            <label for="provinces">Provinsi</label>
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
      
          <div id="infoinput" style="display: none;" class="label label-warning"><b>Silahkan Input PIC</b></div>

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
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script type="text/javascript">


                         

  $(function () {
    


    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 5,
        lengthMenu: [[5, 10, 20], [5, 10, 20]],
        ajax: {
          url:"{{ route('data.prospect') }}",
          type:"POST",
          data: function (d) {
              d.status=1 ;d.url="prospect";
           }
      },
        
        columns: [
          {
      data: 'validation_time',
      name: 'validasi',
      render: function (data, type, full, meta) {
        // Extract just the date from validation_time
        var date = new Date(data);
        var formattedDate = date.toISOString().split('T')[0];

        // Concatenate formattedDate with validation_by
        return formattedDate + '</br>' + full.validasi ;
      }
    },
          
    {data: 'personInCharge', name: 'personInCharge'},
    {data: 'propdetail', name: 'prospect_no'},
    {data: 'province', name: 'province'},
    {data: 'hospital', name: 'hospital'},
    {data: 'namaprod', name: 'product',render: function (data, type, full, meta) {
        // Compile the 'namaprod' and 'qty' columns
        return data + '</br>' + full.qty+" "+ full.config.uom;
      }},
   
    {data: 'value', name: 'value'},
    {data: 'promotion', name: 'promotion'},
    {data: 'review', name: 'review'},
    {data: 'anggaran', name: 'anggaran'},
    {data: 'eta_po_date', name: 'eta_po_date'},
    {data: 'temperature', name: 'temperature'},
    
    {data: 'action', name: 'action', orderable: false, searchable: true},
            
            
           
        ]
    });

    $.ajax({
            url: "{{ route('admin.prospectcreate') }}",
            method: "GET",
            success:function(response){

              var eventSelect = $("#sumberinfofilter");
              populateSelectFromDatalist('sumberinfofilter', response.source.source,"Filter Sumber Informasi");
              //populate from event database
              response.event.forEach(function (event) {
                  var option = $("<option>").val(event.id).text(event.name);
                  eventSelect.append(option);
                });
              
              var tempselect =$("#tempefilter");
              var keterangantempe = "Filter Temperature"
              var placeholderTempeOption = new Option(keterangantempe, '', true, true);
              tempselect.append(placeholderTempeOption);
              tempselect.select2({
                placeholder: keterangantempe,
                width: '100%' // Adjust the width to fit the container
              });
              
              var provfilter = $("#provincefilter");
              populateSelectFromDatalist('provincefilter', response.province,"Filter Provinsi");
              
              var keteranganpic = "Filter PIC"
              var picfilter = $("#picfilter");
              picfilter.select2({
                placeholder: keteranganpic,
                width: '100%' // Adjust the width to fit the container
              });
              response.pic.forEach(function (pic) {
              var option = new Option(pic.name, pic.user_id);
              picfilter.append(option);
              });

              var placeholderPicOption = new Option(keteranganpic, '', true, true);
              picfilter.append(placeholderPicOption);

              var unitSelect = $("#BUfilter");
                populateSelectFromDatalist('BUfilter', response.bunit,"Pilih Business Unit");
                var catSelect=$("#catfilter");
                
                function fetchcat(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('catfilter', response.catopt,"Filter Category");
                    }
                  });
                }
             
                unitSelect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat(selectedunitId);
                });


            }
          });
  });


  $('body').on("click",".btn-cr8",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "{{ route('admin.prospectcreate') }}",
            method: "GET",
            success:function(response){
               
              $("#create-modal").modal("show")
                //$("#thecreators").val(id)
                $("#cr8hospital").val("");
                $("#cr8product").val("");
                $("#eventname").val("");
                $("#qtyinput").val("0");
                

                var today = new Date();

              // Calculate the date after 30 days from today
              var thirtyDaysFromToday = new Date(today);
              thirtyDaysFromToday.setDate(today.getDate() + 30);

              var todayFormatted = today.toISOString().split('T')[0];
              var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];


              $('#etapodatecr8').attr('min', thirtyDaysFromTodayFormatted);


              populateSelectFromDatalist('cr8source', response.source.source,"Pilih Sumber Informasi");

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

                $("#cr8bunit, #cr8category").on("change", function () {
                  var selectedBusinessUnitId = $("#cr8bunit").val();
                  var selectedCategoryId = $("#cr8category").val();

                  if (selectedBusinessUnitId && selectedCategoryId) {
                    populateProductSelect(selectedBusinessUnitId, selectedCategoryId);
                  } else {
                
                    var productSelect = $("#cr8product");
                    productSelect.empty();
                    productSelect.append('<option value="">- Pilih Produk -</option>');
                  }
                });
/*
*/              var anggaranSelect = $("#anggarancr8");
                populateSelectFromDatalist('anggarancr8', response.source.anggaran.review,"Review Anggaran");



                var anggartpSelect = $("#jenisanggarancr8");
                populateSelectFromDatalist('jenisanggarancr8', response.source.anggaran.Jenis,"Pilih Jenis Anggaran");
                

        $("#cr8source").change(function() {
         var selectedOption = $(this).val();
          if (selectedOption === "4") {
           $("#eventname").show();
           
          } else {
            $("#eventname").hide();
            }
          });


              
            }
        })
    });

 

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
               
            }
        })
    })

    // Create


</script>
@endpush