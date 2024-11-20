@extends('layout.backend.app',[
'title' => 'Consumables Prospect Review',
'pageTitle' =>'Consumables Prospect Review',
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
        <form id="filterForm">

        </form>
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
         
          <th>Forecast Code</th>
          <th>Province</th>
          <th>Prov Order No</th>
          <th>Rumah Sakit + Department</th>
          <th>Nama Produk + Qty</th>
          <th>Value Total(IDR)</th>
          <th>Cara Bayar</th>
          <th>PO Target</th>
          <th>Status</th>
          <th>Result PO</th> 
          <th>Result Item</th>
          <th>Result Qty</th>
          <th>Result Total Value</th>

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
 
 $(function () {
  var vendorlist = @json($vendorlist);

  var hospital_id = vendorlist.hospital_id;
  var department_id = vendorlist.department_id;

  $.ajax({
    url: "{{ route('buyerforecastdata') }}",
    type: "POST",
    data: {
    
      hospital_data: hospital_id, // Efficiently extract hospital IDs
      department_data: department_id, // Efficiently extract hospital IDs
      
    },
    success: function(response) {
      
      jsonData = response.data; // Store the fetched data in the variable
      console.log(jsonData);
      consumablestables(jsonData);
     
      var cek = $('#tempefilter').val();
      if (cek == '') {
        cek = '0';
      }
    },
    error: function(xhr, status, error) {
      console.error("AJAX Error: " + status, error);
    },
  })


  
  
  
  




  function consumablestables(dataprospect){
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
        columns:[
          {data: 'creatorname' , name: 'creator'},
          {data: 'forecast_no' , name: 'forecast_code'},
          {data: 'provincedata' , name: 'province'},
          {data: 'province.prov_order_no' , name: 'prov_order_no'},
          {data: 'hospitaldata' , name: 'hospital'},
          {data: 'Items' , name: 'item'},
          {data: 'valuetotal' , name: 'valuetotal'},
          {data: 'payment_method' , name: 'payment_method'},
          {data: 'po_target' , name: 'po_target'},
          {data: 'status' , name: 'status'},
          {data: 'status' , name: 'status'},
        

      
      
      ],
       initComplete: function(settings, json) {
          $('#downloadDataButton').on('click', function() {
            // Trigger DataTables Buttons export functionality
            table.buttons().trigger();
          });
          
        }






      });
      table.clear().rows.add(dataprospect).draw();
    

    }
  }
  

});



</script>
@endpush