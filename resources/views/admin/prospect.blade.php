@extends('layout.backend.app',[
'title' => 'Prospect Review',
'pageTitle' =>'Prospect Review',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('template/backend/sb-admin-2/css')}}/busyload.css">

@endpush




@section('content')

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
       
        @include('dropdownfilter._filter_review_admin')
    
  </div>
</div>
</div>

<div class="card-body ">
  <div class="notify"></div>
  
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


@include('modal._createprospectremarks_modal')

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
            <input type="hidden" required="" id="validator" name="validator" class="" value="{{ Auth::user()->id}}">
            <input type="hidden" required="" id="provcode" name="provcode" class="">
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

            <select required="" name="validation" id="validation" class="form-control">
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
<!--<script src="{{ asset('template/backend/sb-admin-2') }}/jquery.min.js"></script>-->
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/busyload.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.5/xlsx.full.min.js"></script>

<!-- Include JSZip for DataTables Buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<!-- Include DataTables Buttons JS and CSS -->



<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.7.1/css/buttons.dataTables.min.css">
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>



<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.colVis.min.js"></script>


<script type="text/javascript">
  $(document).ready(function() {

    $.busyLoadSetup({
    animation: "slide",
    background: "rgba(0, 114, 255, 0.86)",
    
    }); 

/*
    $.ajaxSetup({
      beforeSend: function() {
        
      },
      complete: function() {
        $.busyLoadFull("hide");
      }
      });
*/

    $.ajax({
      url: "{{ route('admin.prospectcreate') }}",
      method: "GET",
      success: function(response) {
        var eventSelect = $("#sumberinfofilter");
        populateSelectFromDatalist('sumberinfofilter', response.source.source, "Filter Sumber Informasi");
        //populate from event database
        response.event.forEach(function(event) {
          var option = $("<option>").val(event.id).text(event.name);
          eventSelect.append(option);
        });

        var showall = $("<option>").val(0).text("Show All").attr('selected', true);
        eventSelect.prepend(showall);

        var tempselect = $("#tempefilter");
        var keterangantempe = "Filter Temperature";
        var placeholderTempeOption = new Option(keterangantempe, '', true, true);
        tempselect.append(placeholderTempeOption);
        //var tempall = $("<option>").val(0).text("Show All").attr('selected', true);
        // tempselect.prepend(tempall);

        tempselect.select2({
          placeholder: keterangantempe,
          width: '100%' // Adjust the width to fit the container
        });

        var provfilter = $("#provincefilter");
        populateSelectFromDatalist('provincefilter', response.province, "Filter Provinsi");
        var provall = $("<option>").val(0).text("Show All").attr('selected', true);
        provfilter.prepend(provall);

        var keteranganpic = "Pilih Provinsi untuk Memunculkan PIC"
        var picfilter = $("#picfilter");
        picfilter.select2({
          placeholder: keteranganpic,
          width: '100%' // Adjust the width to fit the container
        });


        function populatePicFilter(provinceId) {
            // Clear existing options
            console.log(provinceId);
            picfilter.empty();
            
            // Add placeholder option
            picfilter.append(new Option(keteranganpic, ''));
            
            // Find the selected province object based on provinceId
            var selectedProvince = response.province.find(function(province) {
                return province.id == provinceId;
            });

            if (selectedProvince) {
                // Filter response.pic based on selected province and pic.area
                var filteredPics = response.filterpiclist.filter(function(pic) {
                    // Assuming pic has properties representing the area it belongs to (e.g., pic.area)
                    return pic.area == selectedProvince.wilayah || pic.area == selectedProvince.iss_area_code || pic.area == selectedProvince.prov_order_no;
                });
                
                // Populate picfilter with filtered options
                filteredPics.forEach(function(pic) {
                    picfilter.append(new Option(pic.name, pic.user_id));
                });
            } else {
                console.error('Province not found for ID:', provinceId);
            }
        }

        provfilter.on('change', function() {
          var selectedProvinceId = $(this).val();
          populatePicFilter(selectedProvinceId);
        });


      
        var placeholderPicOption = new Option(keteranganpic, '', true, true);
        picfilter.append(placeholderPicOption);
        var picall = $("<option>").val(0).text("Show All").attr('selected', true);
        picfilter.prepend(picall);

        var unitSelect = $("#BUfilter");
        populateSelectFromDatalist('BUfilter', response.bunit, "Pilih Business Unit");
        var catSelect = $("#catfilter");
        var catall = $("<option>").val(0).text("Show All").attr('selected', true);

        function fetchcat(unitId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
            method: "GET",
            success: function(response) {
              populateSelectFromDatalist('catfilter', response.catopt, "Filter Category");
              $("#catfilter").prepend(catall);
            }
          });
        }
        var unitall = $("<option>").val(0).text("Show All").attr('selected', true);
        unitSelect.prepend(unitall)

        catSelect.select2({
          width: '100%' // Adjust the width to fit the container
        });

        catSelect.prepend(catall);
        unitSelect.on("change", function() {
          var selectedunitId = $(this).val();
          fetchcat(selectedunitId);

        });



        var etafilter = $("#etafilter");
        etafilter.select2({

          width: '100%' // Adjust the width to fit the container
        });

        var sasaran = $("#sasaran");
        sasaran.select2({

          width: '100%' // Adjust the width to fit the container
        });




      }
    });
  });




  var jsonData;
  var ids;

  $.busyLoadFull("show",{spinner: "cubes",
      text: "Please Wait, Loading the Data...",
textPosition: "bottom",maxSize: "150px",
minSize: "150px",fontSize: "2rem",textColor: "white", background: "rgba(0, 114, 255, 0.6)"});
  $.ajax({
    url: "{{ route('data.prospect') }}",
    type: "POST",
    data: {
      status: 1,
      url: "prospect"
    },
    
    //async: false, // Make the call synchronous to ensure data is fetched before DataTable initialization
    success: function(response) {
      jsonData = response.data; // Store the fetched data in the variable
      // console.log(jsonData);
      var cek = $('#tempefilter').val();
      if (cek == '') {
        cek = '0';
      }      //console.log(cek);
      var filterkirim = [$('#sumberinfofilter').val(), cek, $('#provincefilter').val(), $('#picfilter').val(), $('#BUfilter').val(),$('#catfilter').val()];
      //var infoFilter = ;
      //console.log(filterkirim);
      updateData(jsonData, filterkirim);
      DataLine(jsonData);

      $.busyLoadFull("hide");

    },
    error: function(xhr, status, error) {
      console.error("AJAX Error: " + status, error);
    }
  });

  function DataLine(data) {
    // Implement your logic to update the data here
    dataLine = data;
   ids = dataLine.map(function(item) {
        return item.id;
    });
    console.log(ids);
    //console.log(jsonData);
  }

  //console.log(jsonData);
  function updateData(jsonData, params) {
    
   // console.log(jsonData);
   
    var filteredData = jsonData; // Make a copy of the original data
    var newData = jsonData;
    //console.log(filteredData);
    var x = 0;

  //set how much params that change
    for (var i = 0; i < params.length; i++) {
      if (params[i] != '0') {
        x = x + 1;
        console.log(params[i]);
        console.log(i);
      }
 
    }
    


    if (x > 0) {
      for (var i = 0; i < params.length; i++) {
        if (params[i] && params[i] != 0) {
          filteredData = filteredData.filter(function(item) {
    
            //console.log("item"+item.pic_user_id);
            //console.log("params"+params[i]);
            //console.log(item.pic_user_id == params[i]);
            switch (i) {
              case 0:
                return item.prospect_source === params[i];
              case 1:
                return item.temperid == params[i];
            case 2:
               return item.province.name === params[i];
              case 3:
                return item.pic_user_id == params[i];
              case 4:
                return item.unit_id == params[i];
              case 5:
                return item.category == params[i];
              case 6:
            // Calculate the date 'params[i]' months from now
        var targetDate = new Date();
        targetDate.setMonth(targetDate.getMonth() + parseInt(params[i]));
        // Convert item.etadate to a Date object if it's not already
        var etadate = new Date(item.etadate);
        // Check if item.etadate is less than or equal to the target date
        return etadate <= targetDate;
              case 7:
                return item.hospitaltarget == params[i];

                // Add more cases for additional parameters
              default:
                return true;
            }
          });

        }
        newData = filteredData;

      }

      console.log(filteredData);
      DataLine(filteredData);
      // Show message if filteredData is empty
      if (filteredData.length === 0) {
        $('#noDataMessage').text('No data for this filter').show();
      } else {
        $('#noDataMessage').hide();
        // Proceed with displaying or processing the filtered data
      }
      var dataprospect = filteredData;
    } else
      var dataprospect = jsonData;
      const customOrder = { 4: 1, 1: 2, 2: 3, 3: 4, 5: "-1", '-1': 6};

      dataprospect.sort((a,b)=>customOrder[a.temperid] - customOrder[b.temperid]);

     console.log(dataprospect);

     function formatDate(dateString) {
        if(dateString){
        const date = new Date(dateString);
        const day = String(date.getDate()).padStart(2, '0');
        const month = date.toLocaleString('default', { month: 'long' });
        const year = date.getFullYear();
        return `${day}-${month}-${year}`;}
        else return "";
      }


    var prospectsheet = newData.map(function(datz) {
      return {
        TanggalSubmit: formatDate(datz.validation_time),
        Creator: datz.creator.name,
        ProspectNo: datz.prospect_no,
        Province: datz.province.name,
        SumberInfoProspect: datz.prospect_source,
        PIC: datz.personInCharge,
        AM: datz.AMNSM,
        City: datz.city,
        Hospital: datz.hospital.name,
        Department: datz.department.name,
        BusinessUnit:datz.unit,
        ProductCategory: datz.category,
        Brand: datz.brand,
        ConfigName: datz.config.name,
        ConfigNumber: datz.configno,
        UnitPrice: datz.submitted_price,
        Qty: datz.qty,
        uom: datz.uom,
        Value: datz.submitted_price * datz.qty,
        FirstOffer: formatDate(datz.review.first_offer_date),
        Demo: formatDate(datz.review.demo_date),
        Presentation: formatDate(datz.review.presentation_date),
        Lastoffer: formatDate(datz.review.last_offer_date),
        UserStatus: datz.review.user_status,
        PurchasingStatus: datz.review.purchasing_status,
        Direksi: datz.review.direksi_status,
        Anggaran: datz.review.anggaran_status,
        JenisAnggaran: datz.review.jenis_anggaran,
        InformasiTambahan: datz.review.comment,
        Chance: (datz.review.chance * 100).toFixed(0) + ' %',
        EtaPoDate: formatDate(datz.eta_po_date),
        Temperature: datz.temperature.tempName,
        NextAction: datz.review.next_action
      }

    });


    console.log(prospectsheet);
    //console.log(dataprospect);
    initialProspectTable(dataprospect);
    $('#dlexcel').on('click', function() {
      downloadExcel(prospectsheet);


    });

  };




  // Handle the case when the filter dropdown is cleared

  $('#sumberinfofilter,#tempefilter,#provincefilter,#picfilter,#BUfilter,#catfilter,#etafilter,#sasaran').on('change', function() {

    var selectedValue = $('#sumberinfofilter').val();
    var selectedText = $('#sumberinfofilter').find('option:selected').text();
    // console.log(selectedValue);

    var tempValue = $('#tempefilter').val();
    if (tempValue == '') {
      tempValue = '0'
    }
    var tempText = $('#tempefilter').find('option:selected').text();

    var provValue = $('#provincefilter').val();
    var provText = $('#provincefilter').find('option:selected').text();

    var picValue = $('#picfilter').val();
    var picText = $('#picfilter').find('option:selected').text();

    var buValue = $('#BUfilter').val();
    var buText = $('#BUfilter').find('option:selected').text();

    var catValue = $('#catfilter').val();
    var catText = $('#catfilter').find('option:selected').text();

    var etaValue = $('#etafilter').val();
    var etaText = $('#etafilter').find('option:selected').text();

    var sasaranValue = $('#sasaran').val();
    var sasaranText = $('#sasaran').find('option:selected').text();


    //console.log(catValue + catText);

    if (selectedValue != 0) {
      selectedValue = selectedText
    }
   
    if (provValue != 0) {
      provValue = provText
    }
    if (catValue != 0) {
      catValue = catText
    }
    //console.log(jsonData)
    // selectedValue=$(this).text();


    var filterupdate = [selectedValue, tempValue, provValue, picValue, buValue,catValue,etaValue,sasaranValue];

    updateData(jsonData, filterupdate);
    //console.log(selectedText)
  });


//function to download to excel
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


  function updateColumnPriorities() {
    var columns = table.settings().init().columns;
    columns.forEach(function(column, index) {
      switch (index) {
        case 3: // Set new responsive priority for the first column
          column.responsivePriority = 13;
          break;
        case 5: // Set new responsive priority for the first column
          column.responsivePriority = 12;
          break;
        default: // Set new responsive priority for the second column
          column.responsivePriority = 0;
          break;
          // Add cases for other columns using their indexes here

      }
    });
    // Redraw the DataTable to reflect the changes
    table.columns.adjust().draw();
  }




  function initialProspectTable(dataprospect) {
    //console.log(dataprospect);

    if (dataprospect && dataprospect.length > 0) {
      
      var existingTable = $('.data-table').DataTable();
      if (existingTable) {
        existingTable.destroy(); // Destroy the existing DataTable
      }


      var table = $('.data-table').DataTable({

        processing: true,
        serverSide: false,
        lengthMenu: [
          [5, 10, 20],
          [5, 10, 20]
        ],
        pageLength: 5,
        responsive: true,
        rowReorder: {
          selector: 'td:nth-child(2)'
        },


        dom: 'filprtip', // Include buttons in the DataTable layout
        
        order:[],
        
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

        columns: [{
            data: 'submitdate',
            name: 'submitdate',
            responsivePriority: 11,
            targets: 11
          },
          {
            data: 'validasi',
            name: 'validasi',
            responsivePriority: 13,
            targets: 13
          },
          {
            data: 'personInCharge',
            name: 'personInCharge',
            responsivePriority: 10,
            targets: 10
          },
          {
            data: 'propdetail',
            name: 'prospectdetail',
            responsivePriority: 0,
            targets: 13
          },
          {
            data: 'provincedata',
            name: 'provincedata',
            responsivePriority: 12,
            targets: 12
          },
          {
            data: 'hospitaldata',
            name: 'hospitaldata',
            responsivePriority: 1,
            targets: 1
          },
          {
            data: 'namaprod',
            name: 'namaprod',
            render: function(data, type, full, meta) {
              // Compile the 'namaprod' and 'qty' columns

              return data + '</br>' + full.qty + " " + full.uom;
              //console.log(full.config);
            },
            searchable: true,
            responsivePriority: 2,
            targets: 2
          },

          {
            data: 'value',
            name: 'value',
            responsivePriority: 3,
            targets: 3
          },
          {
            data: 'promotion',
            name: 'promotion',
            responsivePriority: 5,
            targets: 5
          },
          {
            data: 'reviewStats',
            name: 'reviewStats',
            responsivePriority: 6,
            targets: 6
          },
          {
            data: 'anggaran',
            name: 'anggaran',
            responsivePriority: 7,
            targets: 7
          },
          {
            data: 'eta_po_date',
            name: 'eta_po_date',
            responsivePriority: 8,
            targets: 8
          },
          {
            data: 'temperaturebtn',
            name: 'temperature',
            responsivePriority: 4,
            targets: 4
          },

          {
            data: 'action',
            name: 'action',
            orderable: false,
            searchable: true,
            responsivePriority: 9,
            targets: 9
          },



        ],

        initComplete: function(settings, json) {
          $('#downloadDataButton').on('click', function() {
            // Trigger DataTables Buttons export functionality
            table.buttons().trigger();
          });
          
        }

      });
      table.clear().rows.add(dataprospect).draw();
      updateColumnPriorities;

    } else {
      console.error("Data is null or empty;")
    }



  }
  //$('#downloadDataButton').on('click', function () {
  // Trigger DataTables Buttons export functionality
  //  table.buttons('.buttons-excel').trigger();
  // });
  var datarem=datarem();
  //console.log(datarem);

  $('body').on("click", ".btn-remarks", function() {
    var id = $(this).data("id");
    console.log(id);
    var remarksData = $(this).data("remarks");
//var remarksArray = JSON.parse(remarksData);
console.log(remarksData);
    $('#prospectid').val(id);
    populateSelectFromDatalist('cr8type',datarem.tiperem,'Pilih Tipe Remarks');
    populateSelectFromDatalist('cr8colupdate',datarem.column,'Pilih Kolom yang diUpdate');
    
    



    $("#prospectremarks-modal").modal("show");
  });

  $("#createRemForm").on("submit", function(e) {
    e.preventDefault()

    $.ajax({
      url: "{{ route('remarks.store') }}",
      method: "POST",
      data: $(this).serialize(),
      success: function() {
        $("#prospectremarks-modal").modal("hide")
        //$('.data-table').DataTable().ajax.reload();
        flash("success", "Create Remarks Done");
        var cek = $('#tempefilter').val();
      if (cek == '') {
        cek = '0';
      }
        var filterkirim = [$('#sumberinfofilter').val(), cek, $('#provincefilter').val(), $('#picfilter').val(), $('#BUfilter').val(),$('#catfilter').val()];
      //var infoFilter = ;
      //console.log(filterkirim);
    
      updateData(jsonData, filterkirim);
      DataLine(jsonData);

      }
    })
  })


  $('body').on("click", ".btn-cr8", function() {
    var id = $(this).attr("id")

    $.ajax({
      url: "{{ route('admin.prospectcreate') }}",
      method: "GET",
      success: function(response) {

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


        populateSelectFromDatalist('cr8source', response.source.source, "Pilih Sumber Informasi");

        var provinceSelect = $("#cr8province");
        populateSelectFromDatalist('cr8province', response.province, "Pilih Provinsi");

        var hosinput = $("#cr8hospital");


        function fetchHospitals2(provinceId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
            method: "GET",
            success: function(response) {

              populateSelectFromDatalist('cr8hospital', response.hosopt, "Pilih Rumah Sakit");

            }
          });
        }

        provinceSelect.on("change", function() {
          var selectedProvinceId = $(this).val();
          fetchHospitals2(selectedProvinceId);
        });

        var deptSelect = $("#cr8department");
        populateSelectFromDatalist('cr8department', response.dept, "Pilih Departemen");

        var unitSelect = $("#cr8bunit");
        populateSelectFromDatalist('cr8bunit', response.bunit, "Pilih Business Unit");

        var catSelect = $("#cr8category");

        function fetchcat(unitId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
            method: "GET",
            success: function(response) {
              populateSelectFromDatalist('cr8category', response.catopt, "Pilih Kategori Produk");
            }
          });
        }

        unitSelect.on("change", function() {
          var selectedunitId = $(this).val();
          fetchcat(selectedunitId);
        });

        $("#cr8bunit, #cr8category").on("change", function() {
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
         */
        var anggaranSelect = $("#anggarancr8");
        populateSelectFromDatalist('anggarancr8', response.source.anggaran.review, "Review Anggaran");



        var anggartpSelect = $("#jenisanggarancr8");
        populateSelectFromDatalist('jenisanggarancr8', response.source.anggaran.Jenis, "Pilih Jenis Anggaran");


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

  $("#createForm").on("submit", function(e) {
    e.preventDefault()

    $.ajax({
      url: "/admin/prospect",
      method: "POST",
      data: $(this).serialize(),
      success: function() {
        $("#create-modal").modal("hide")
        $('.data-table').DataTable().ajax.reload();
        flash("success", "Data berhasil ditambah")

      }
    })
  })


  function storeSequenceData() {
        $.ajax({
            url: '{{ route("sequence.up") }}', // Replace 'store.sequence.data' with your actual route name
            type: 'POST',
            data: {
                sequenceData: JSON.stringify(ids) // Convert JSON data to a string before sending
            },
            success: function(response) {
                // Handle success response from the server
                console.log(response);
            },
            error: function(xhr, status, error) {
                // Handle error response from the server
                console.error(xhr.responseText);
            }
        });
    }

  // Create
</script>
@endpush