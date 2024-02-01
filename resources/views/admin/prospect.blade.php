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
        <button class="btn btn-primary btn-sm ml-2" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        Filter
        </button>
<!--
        <button class="btn btn-primary btn-sm ml-2q" type="button" id="downloadDataButton">
        Download data
        </button>-->

        <button class="btn btn-primary btn-sm ml-2q" type="button" id="dlexcel">Download to Excel </button>


        <div class="collapse" id="filterCollapse">
         <div class="row mt-4">
          <div class="col-4">
          <div class="col col-lg-8">
            <div class="form-group">
            <label for="sumberinfofilter">Sumber Prospect :</label>
             <select id="sumberinfofilter" name="sumberinfofilter" class="form-control dropdown" required=""  >
             <option value="8888" selected>Show All</option> 
               </select>
              </div>
          </div>  
            <div class="col col-lg-8">
              <div class="form-group">
                  <label for="tempefilter">Temperature :</label>
                   <select id="tempefilter" name="tempefilter" class="form-control dropdown" required=""  >
                      <option value="8888" selected>Show All</option>
                      <option value="1">HOT PROSPECT</option>
                      <option value="2">PROSPECT</option>
                      <option value="3">FUNNEL</option>
                      <option value="4">DROP</option>
                   </select>
              </div>
            </div>
            </div>
        
        <div class="col-4">
            <div class="col col-lg-8">
              <div class="form-group">
                <label for="provincefilter">Province :</label>
                <select id="provincefilter" name="provincefilter" class="form-control dropdown" required=""  >
                <option value="8888" selected>Show All</option>
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
             <option value="8888" selected>Show All</option>
          </select>
        </div>
      </div>
      <div class="col col-lg-8">
        <div class="form-group">
            <label for="catfilter">Product Category :</label>
             <select id="catfilter" name="catfilter" class="form-control dropdown" required=""  >
             <option value="8888" selected>Show All</option>
          </select>
        </div>
      </div>

    </div>

    </div>
    </div>
  </div>
    
        <div class="card-body ">
                 
            <div class="table-responsive ml-2 mr-2 drag table-hover">    
                <table class="table table-bordered data-table ">
                    <thead>
                        <tr>
                        <th>Creator</th>
                        <th>Validator</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<!-- Include JSZip for DataTables Buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- Include DataTables Buttons JS and CSS -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>


<script type="text/javascript">




                         

  $(document).ready(function () {
    
    
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
              
              var showall = $("<option>").val(8888).text("Show All").attr('selected', true);
              eventSelect.prepend(showall);

              
              var tempselect =$("#tempefilter");
              var keterangantempe = "Filter Temperature"
              var placeholderTempeOption = new Option(keterangantempe, '', true, true);
              tempselect.append(placeholderTempeOption);
              tempselect.select2({
                placeholder: keterangantempe,
                width: '100%' // Adjust the width to fit the container
              });
          var tempall = $("<option>").val(8888).text("Show All").attr('selected', true);
           tempselect.prepend(tempall);
              
              var provfilter = $("#provincefilter");
              populateSelectFromDatalist('provincefilter', response.province,"Filter Provinsi");
              var provall = $("<option>").val(8888).text("Show All").attr('selected', true);
              provfilter.prepend(provall);

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
              var picall = $("<option>").val(8888).text("Show All").attr('selected', true);
              picfilter.prepend(picall);

              var unitSelect = $("#BUfilter");
                populateSelectFromDatalist('BUfilter', response.bunit,"Pilih Business Unit");
                var catSelect=$("#catfilter");
                var catall = $("<option>").val(8888).text("Show All").attr('selected', true);
                function fetchcat(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('catfilter', response.catopt,"Filter Category");
                      $("#catfilter").prepend(catall);
                    }
                  });
                }
                var unitall = $("<option>").val(8888).text("Show All").attr('selected', true);
                unitSelect.prepend(unitall)
                
                catSelect.prepend(catall);
                unitSelect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat(selectedunitId);
                  
                });


            }
          });
  });




    var jsonData;

    $.ajax({
    url: "{{ route('data.prospect') }}",
    type: "POST",
    data: { status: 1, url: "prospect" },
    //async: false, // Make the call synchronous to ensure data is fetched before DataTable initialization
    success: function (response) {
        jsonData = response.data; // Store the fetched data in the variable
        //console.log(jsonData);

    function updateData(SourceFilter1,SourceFilter2,SourceFilter3){
          var filteredData;
          var newData;
          console.log(SourceFilter1);
          if(SourceFilter1==8888||!SourceFilter1){
            filteredData=jsonData;
            newData=jsonData;
          }else{
            filteredData=jsonData.filter(function(sfilterdt){
              return sfilterdt.prospect_source===SourceFilter1;
            })
            newData=jsonData.filter(function(sfilterdt){
              return sfilterdt.prospect_source === SourceFilter1;
            })
          }
      
        
      if(SourceFilter2){

        if(SourceFilter2==8888||!SourceFilter2){
            filteredData=filteredData;
            newData=filteredData;
          }else{
            filteredData=filteredData.filter(function(sfilterdt){
              return sfilterdt.temperaturedata===SourceFilter2;
            })
            newData=filteredData.filter(function(sfilterdt){
              return sfilterdt.temperaturedata === SourceFilter2;
            })
          }
        } 

      if(SourceFilter3){

        if(SourceFilter3==8888||!SourceFilter3){
            filteredData=filteredData;
            newData=filteredData;
          }else{
            filteredData=filteredData.filter(function(sfilterdt){
              return sfilterdt.province.name===SourceFilter3;
            })
            newData=filteredData.filter(function(sfilterdt){
              return sfilterdt.province.name === SourceFilter3;
            })
          }
        } 

       console.log(newData);

        var dataprospect=filteredData.map(function(item){
          return{
            pic:item.personInCharge,
            validasi:item.validasi,
            submitdate:item.submitdate,
            //validasi: item.validation_time,
            propdetail:item.propdetail,
            province: item.provincedata,
            hospital: item.hospitaldata,
            namaprod:item.namaprod,
            qty:item.qty,
            uom:item.config.uom,
            value:item.value,
            promotion:item.promotion,
            review:item.reviewStats,
            anggaran:item.anggaran,
            etaPoDate:item.eta_po_date,
            temperature:item.temperature,
            action:item.action,
          }
        });

        //console.log(newData);

        var prospectsheet = newData.map(function(datz){
          return{
            TanggalSubmit : datz.validation_time,
            Creator : datz.creator.name,
            ProspectNo : datz.prospect_no,
            Province : datz.province.name,
            SumberInfoProspect: datz.prospect_source,
            PIC : datz.personInCharge,
            AM : datz.AMNSM,
            City : datz.city,
            Hospital : datz.hospital.name,
            Department :datz.department.name,
            ProductCategory: datz.category,
            Brand: datz.brand,
            ConfigName : datz.config.name,
            ConfigNumber : datz.configno,
            UnitPrice : datz.submitted_price,
            Qty : datz.qty,
            Value:datz.submitted_price*datz.qty,
            FirstOffer : datz.review.first_offer_date,
            Demo : datz.review.demo_date,
            Presentation :datz.review.presentation_date,
            Lastoffer : datz.review.last_offer_date,
            UserStatus: datz.review.user_status,
            PurchasingStatus : datz.review.purchasing_status,
            Direksi : datz.review.direksi_status,
            Anggaran : datz.review.anggaran_status,
            JenisAnggaran : datz.review.jenis_anggaran,
            InformasiTambahan : datz.review.comment,
            Chance: (datz.review.chance * 100).toFixed(0) +' %',

            EtaPoDate: datz.eta_po_date,
            Temperature : datz.temperaturedata,
            NextAction : datz.review.next_action
      
        
          }

          console.log(prospectsheet);
          $('#dlexcel').on('click', function () {
            downloadExcel(prospectsheet);
        });


        });
       
        //console.log(prospectsheet);
        initialProspectTable(dataprospect);
        $('#dlexcel').off('click').on('click', function () {
                    downloadExcel(prospectsheet);
        });
      };

      var infoFilter = $('#sumberinfofilter').val();
        console.log(infoFilter);
        updateData(infoFilter,$('#tempefilter').val(),$('#provincefilter').val());

        
       

        $('#sumberinfofilter,#tempefilter,#provincefilter').on('change', function () {
          
          var selectedValue = $('#sumberinfofilter').val();
          var selectedText= $('#sumberinfofilter').find('option:selected').text();
         // console.log(selectedValue);

          var tempValue = $('#tempefilter').val();
          var tempText= $('#tempefilter').find('option:selected').text();

          var provValue = $('#provincefilter').val();
          var provText= $('#provincefilter').find('option:selected').text();
          
          if(selectedValue!=8888){
            selectedValue=selectedText
          }
          if(tempValue!=8888){
            tempValue=tempText
          }
          if(provValue!=8888){
            provValue=provText
          }
             // selectedValue=$(this).text();
           
            updateData(selectedValue,tempValue,provValue); 
          //console.log(selectedText)
        });

        


        // Handle the case when the filter dropdown is cleared
        $('#clearFilterButton').on('click', function () {
          // Set the dropdown to "All" or an appropriate default value
          $('#sumberinfofilter').val(8888).trigger('change');
        });


        
      },
    error: function (xhr, status, error) {
        console.error("AJAX Error: " + status, error);
    }
  });

  function downloadExcel(data) {
    // Create a new Excel workbook
    var workbook = XLSX.utils.book_new();

    // Convert data to worksheet
    var worksheet = XLSX.utils.json_to_sheet(data);

    // Add the worksheet to the workbook
    XLSX.utils.book_append_sheet(workbook, worksheet, 'Prospects');

    // Save the workbook as an Excel file
    XLSX.writeFile(workbook, 'Prospects.xlsx');
}


  function initialProspectTable(dataprospect){
    //console.log(dataprospect);
  
    if(dataprospect && dataprospect.length >0){
      var existingTable = $('.data-table').DataTable();
    if (existingTable) {
        existingTable.destroy(); // Destroy the existing DataTable
    }


    var table = $('.data-table').DataTable({
      
        processing: true,
        serverSide: false,
        lengthMenu: [[5, 10, 20], [5, 10, 20]],
        pageLength: 5,
        
       dom: 'filprtip', // Include buttons in the DataTable layout
        /*buttons: [

        {
            extend: 'excel',
            text: 'Dowload Excel Format Web',
            exportOptions: {
                modifier: {
                    page:'all' // Include all data in the Excel export
                }
            }
        },
        
       
    ], */ 
   // data : dataprospect,
                
    columns: [
      {data: 'submitdate' , name: 'submitdate'},
     {
            data: 'validasi',
            name: 'validasi',
     }  ,        
    {data: 'pic', name: 'pic'},
   {data: 'propdetail', name: 'prospect_no'},
    {data: 'province', name: 'province'},
    {data: 'hospital', name: 'hospital'},
   {data: 'namaprod', name: 'namaprod',render: function (data, type, full, meta) {
        // Compile the 'namaprod' and 'qty' columns
        return data + '</br>' + full.qty+" "+ full.uom;
      },searchable:true},
   
    {data: 'value', name: 'value'},
   {data: 'promotion', name: 'promotion'},
    {data: 'review', name: 'review'},
    {data: 'anggaran', name: 'anggaran'},
   {data: 'etaPoDate', name: 'eta_po_date'},
    {data: 'temperature', name: 'temperature'},
    
  {data: 'action', name: 'action', orderable: false, searchable: true},
            
      
           
        ],


    initComplete: function (settings, json) {
        $('#downloadDataButton').on('click', function () {
            // Trigger DataTables Buttons export functionality
            table.buttons().trigger();
        });
    }

    });
    //console.log(dataprospect);
    table.clear().rows.add(dataprospect).draw();
  }
  else {
    console.error("Data is null or empty;")
  }



  }
    //$('#downloadDataButton').on('click', function () {
      // Trigger DataTables Buttons export functionality
    //  table.buttons('.buttons-excel').trigger();
   // });
  


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