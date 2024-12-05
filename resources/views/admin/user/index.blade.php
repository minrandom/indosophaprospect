@extends('layout.backend.app',[
    'title' => 'Manage User',
    'pageTitle' =>'Manage User',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-modal">
          Tambah Data
        </button>
    </div>
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Area</th>
                            <th>Status</th>
                            <th>Action</th>
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
            <label for="n">Name</label>
            <input type="" required="" id="n" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="ln">Long Name</label>
            <input type="" required="" id="ln" name="longname" class="form-control">
        </div>
        <div class="form-group">
            <label for="g">Gender</label>
            <select name="gender" id="g" class="form-control">
                <option disabled="">- PILIH Gender -</option>
                <option value="M">MALE</option>
                <option value="F">FEMALE</option>
            </select>
        </div>
        <div class="form-group">
            <label for="e">Email</label>
            <input type="" required="" id="e" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="p">Password</label>
            <input type="password" required="" id="p" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="r">Role</label>
            <select name="role" id="r" class="form-control">
                <option disabled="">- PILIH ROLE -</option>
                <option value="admin">Admin</option>
                <option value="bu">Bussiness Unit</option>
                <option value="fs">Field Sales</option>
                <option value="am">Area Manager</option>
                <option value="nsm">National Sales Manager</option>
            </select>
        </div>
        <div class="form-group bupick" id="buPickContainer" style='display: none'>
            <label for="BU">BU</label>
            <select name="bu" id="bu" class="form-control">
                <option disabled="">- PILIH Bussiness Unit -</option>
                <option value="BUURO">Urology</option>
                <option value="BUCV">Cardiovascular</option>
                <option value="BUICU">ICU / Anesthesia</option>
                <option value="BUSWP">Sarana OK</option>
                <option value="BUESU">Electro Surgery</option>
                <option value="BUINS">Instrument</option>
                <option value="BURT">RT Oncology</option>
                <option value="BUMOT">MOT</option>
            </select>
        </div>
        <div class="form-group area" id="areacontain" style='display: none'>
            <label for="area">AREA</label>
            <select name="area" id="area" class="form-control">
                <option disabled="">- PILIH Area -</option>
             
            </select>
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
            <label for="name">Name</label>
            <input type="hidden" required="" id="id" name="id" class="form-control">
            <input type="" required="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="" required="" id="email" name="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="role">Role</label>
            <select name="role" id="role" class="form-control">
                <option disabled="">- PILIH ROLE -</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
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
        ajax: "{{ route('admin.user.index') }}",
        columns: [
            {data: 'DT_RowIndex' , name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'role', name: 'role'},
            {data: 'Area', name: 'Area'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });



  });


  $(document).ready(function() {
        // Function to toggle BU dropdown visibility
      $('#area').select2({width:'100%'});

        function loadProvinces() {
        $.ajax({
            url: '{{ route("admin.province.index") }}', // Laravel route for provinces
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                // Assuming response is an array of provinces
                // with each province having at least id and name properties
                var areaSelect = $('#area');
                console.log(response);
                // Clear existing options, keep the default disabled option
                areaSelect.find('option:not(:first)').remove();
                
                // Populate dropdown with provinces from response
                response.data.forEach(function(province) {
                    areaSelect.append(
                        `<option value="${province.prov_order_no}">
                            ${province.name}
                        </option>`
                    );
                });
            },
            error: function(xhr, status, error) {
                console.error('Error loading provinces:', error);
                
                // Optional: Show user-friendly error message
                var areaSelect = $('#area');
                areaSelect.append(
                    `<option value="">Error loading provinces</option>`
                );
            }
        });
      }


      function clearAreaDropdown() {
          var areaSelect = $('#area');
          var areaContainer = $('#areacontain');
          
          // Reset to default option
          areaSelect.prop('selectedIndex', 0);
          
          // Remove any dynamically added options
          areaSelect.find('option:not(:first)').remove();
          
          // Hide the area dropdown
          areaContainer.hide();
      }

         // Area options for different roles
         const areaOptions = {
            am: { // Area Manager - Specific Areas
                type: 'Area',
                options: [
                    { value: 'B1', label: 'ACEH / SUMUT' },
                    { value: 'B2', label: 'SUMBAR / RIAU / KEPRI' },
                    { value: 'B3', label: 'JAMBI / BENGKULU / SUMSEL / BABEL / LAMPUNG' },
                    { value: 'B4', label: 'BANTEN / JAKARTA 3' },
                    { value: 'B5', label: 'JAWA BARAT' },
                    { value: 'B6', label: 'JAKARTA 1' },
                    { value: 'B7', label: 'JAKARTA 2' },
                    { value: 'T1', label: 'JAKARTA 4' },
                    { value: 'T2', label: 'JATENG / JOGJA' },
                    { value: 'T3', label: 'BALI / NTT / NTB' },
                    { value: 'T4', label: 'KALIMANTAN' },
                    { value: 'T5', label: 'SULAWESI' },
                    { value: 'T6', label: 'INDONESIA TIMUR' },
                    { value: 'T7', label: 'JAWA TIMUR' },
                ]
            },
            nsm: { // National Sales Manager - Regions
                type: 'Region',
                options: [
                    { value: 'T', label: 'AREA TIMUR INDONESIA' },
                    { value: 'B', label: 'AREA BARAT INDONESIA' }
                ]
            }
        };

        // Function to populate and show/hide area dropdown
        function updateAreaDropdown(selectedRole) {
            var areaContainer = $('#areacontain');
            var areaSelect = $('#area');
            
            // Reset area dropdown
            areaSelect.empty().append('<option disabled="" selected="">- SELECT ' + 
                (areaOptions[selectedRole] ? areaOptions[selectedRole].type.toUpperCase() : 'AREA') + ' -</option>');
            
            // Check if the role has specific area options
            if (areaOptions[selectedRole]) {
                // Populate dropdown with specific options
                areaOptions[selectedRole].options.forEach(function(option) {
                    areaSelect.append(`<option value="${option.value}">${option.label}</option>`);
                });
                
                // Show area dropdown
                areaContainer.show();
            } else {
                // Hide area dropdown
                areaContainer.hide();
            }
        }

        // Dropdown change event handler
   

        $('#r').on('change', function() {
            var selectedRole = $(this).val();
            
            // Hide BU dropdown first
            $('#buPickContainer').hide();
            $('#bu').prop('selectedIndex', 0);
            if(['bu', 'admin'].includes(selectedRole)){
              $('#areacontain').hide();
            }
            
            // Handle other dropdowns based on role
            if (selectedRole === 'bu') {
          
              clearAreaDropdown();
                $('#buPickContainer').show();
            
            } else {
              $('#buPickContainer').hide();
            }
            
            if (selectedRole === 'fs') {
              clearAreaDropdown();
            // Show area dropdown and load provinces
            $('#areacontain').show();
            loadProvinces();}
          
            if (['am', 'nsm'].includes(selectedRole)) {
              clearAreaDropdown();
              $('#areacontain').show();
                // Show area dropdown for specific roles
                updateAreaDropdown(selectedRole);
            }

          



        });



    });




    



    // Reset Form
        function resetForm(){
            $("[name='name']").val("")
            $("[name='email']").val("")
            $("[name='password']").val("")
        }
    //

    // Create 

        $()


    $("#createForm").on("submit",function(e){
        e.preventDefault()

        $.ajax({
            url: "{{ route('admin.user.store') }}",
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

    // Edit & Update
    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        
        $.ajax({
            url: "/admin/user/"+id+"/edit",
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show")
                $("#id").val(response.id)
                $("#name").val(response.name)
                $("#email").val(response.email)
                $("#role").val(response.role)
            }
        })
    });

    $("#editForm").on("submit",function(e){
        e.preventDefault()
        var id = $("#id").val()

        $.ajax({
            url: "/admin/user/"+id,
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

    $('body').on("click",".btn-delete",function(){
        var id = $(this).attr("id")
        $(".btn-destroy").attr("id",id)
        $("#destroy-modal").modal("show")
    });

    $(".btn-destroy").on("click",function(){
        var id = $(this).attr("id")

        $.ajax({
            url: "/admin/user/"+id,
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