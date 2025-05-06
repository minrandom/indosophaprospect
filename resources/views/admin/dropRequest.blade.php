@extends('layout.backend.app',[
'title' => 'Drop Request Data',
'pageTitle' =>'Drop Request Data',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
  <div class="card-header">
    <!-- Button trigger modal -->


  </div>

  <div class="card-body">
    <div class="table-responsive">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>Kode Prospect</th>
            <th>RS</th>
            <th>Dept</th>
            <th>Business Unit</th>
            <th>Config</th>
            <th>Request By</th>
            <th>Reason / Description</th>
            <th>Validation By</th>
            <th>Bu Noted?</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>

</div>
@include('modal._dropsuccessvalidation_modal')

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/regencies.js"></script>

<script type="text/javascript">
  $(function() {

    $.ajax({
      url: "{{ route('admin.prospect.droplistdata') }}", // Replace with your endpoint
      dataType: 'json',
      success: function(response) {
        // Here we have the data returned as 'response'
         // Log the response to see the data structure
        // Filter data
        var userRole = response.additionalData.roles;
        var userArea= response.additionalData.area;
        console.log(userArea);
        var filteredData = response.data.filter(function(item) {
          switch (userRole) {
            case 'nsm':
              if (item.wilayah == userArea) {
                return true;}else 
                return false // Include this item}
              break;
            case 'am':
              if (item.issareacode == userArea ) {
                return true;}else
                return false
              break;
            case 'fs':
              if (item.regioncode == userArea ) {
                return true;}else 
                return false
              break;
            case 'admin':
              return true
              break;
            default:return false
              
          }

        });

        console.log(filteredData)
       // Now we can pass the data to the DataTable

        populateDataTable(filteredData);
      },
      error: function(xhr, status, error) {
        console.log("Error fetching data: " + error);
      }
    });

    function populateDataTable(data) {
    var table = $('.data-table').DataTable({
        data: data, // Pass the data to the DataTable
        columns: [
            { data: 'prospect_no', title: 'Prospect No' },
            { data: 'hospital', title: 'Hospital' },
            { data: 'dept', title: 'Department' },
            { data: 'bunit', title: 'Bussiness Unit' },
            { data: 'config', title: 'Config' },
            { data: 'requestData', title: 'Request Data' },
            { data: 'requestReason', title: 'Request Reason' },
            { data: 'validator', title: 'Validator' },
            { data: 'bunoted', title: 'BU Noted' },
            { data: 'statusreq', title: 'Status' }
        ]
    });
  }

  });



  $('body').on("click", ".btn-validasi", function() {
    var id = $(this).attr("id");


    $.ajax({
      url: "{{ route('admin.prospect.dropdata',['dropRequest' => ':dropRequest']) }}".replace(':dropRequest', id),
      method: "GET",
      data: $(this).serialize(),
      success: function(response) {
        $("#dropsuccess-modal").modal("show");
        $('#updateTo').select2({
          placeholder: "APPROVE",
          width: '100%' // Adjust the width to fit the container
        });
        $('#RS').val(response.data.hospital);
        $('#Config').val(response.data.config);
        $('#thecreators').val(response.data.request);
        $('#prospectCode').val(response.data.prospect.prospect_no);
        $('#idprospect').val(id);
        $('#cr8messages').val(response.data.keterangan);
        $('#cr8reason').val(response.data.reason);

        //populateSelectFromDatalist('cr8colupdate',datarem.column,'Pilih Kolom yang diUpdate');


      }
    })

  })


  function updateRequest(successMessage) {
    var form = $('#requestDropSuccessForm');
    // Serialize the form data
    var formData = form.serialize();
    var id = $('#idprospect').val();
    // Get the updateTo value from the form
    $.ajax({
      url: "{{ route('admin.prospect.dropupdate',['dropRequest' => ':dropRequest']) }}".replace(':dropRequest', id),
      method: "PATCH",
      data: formData,
      success: function() {
        //$("#create-modal").modal("hide");
        //$('.data-table').DataTable().ajax.reload();
        $("#requestDropSuccessForm")[0].reset();
        flash("success", successMessage);
        document.querySelector(".notify").scrollIntoView({
          behavior: "smooth",
          block: "start"
        });

      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log('AJAX Error:', textStatus, errorThrown);

        flash("Danger", "Tolong diisi bagian yang masih kosong!!");
        document.querySelector(".notify").scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }

    });
  };


  $('#btn-update').on('click', function(e) {
    $(this).focus();
    e.preventDefault();
    $(document.activeElement);
    updateRequest("Data Sudah  TerUpdate , Silahkan Hubungi AM/FS dan Kabari Bussiness Unit yang bersangkutan.");
    $("#dropsuccess-modal").modal("hide");
  });


  function flash(type, message) {
    $(".notify").html(`<div class="alert alert-` + type + ` alert-dismissible fade show" role="alert">
                              ` + message + `
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </div>`)
  }
</script>
@endpush