@extends('layout.backend.app',[
    'title' => 'RS',
    'pageTitle' =>'Rumah Sakit',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <a type="button" class="btn btn-primary" href="{{ route('admin.hospitalcreate')}}">
          Tambah Data
</a>
        
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>Kode RS</th>
                            <th>Nama RS</th>
                            <th>Province</th>
                            <th>Prov Order No</th>
                            <th>Category</th>
                            <th>City</th>
                            <th>Alamat RS</th>
                            <th>Kepemilikan</th>
                            <th>Tipe</th>
                            <th>Class</th>
                            <th>Akreditasi</th>
                            <th>Sasaran</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    
</div>

<!-- Modal Create
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
            <label for="name">Nama Rumah Sakit</label>
            <input type="" required placeholder="ABCDE , RS" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="province">Pilih Provinsi</label>
            <select id="province" name="province" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="city">Pilih Kota/Kabupaten</label>
            <select id="city" name="city" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="prc">Prov Region Code</label>
            <input type="" required="" id="prc" name="prov_region_code" class="form-control">
        </div>
        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-store">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div> -->
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
            <input type="hidden" required="" id="data" name="data" class="form-control">
            <input type="" required="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="province">Provinsi</label>
            <select id="province" name="province" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="city">Kota/Kabupaten</label>
            <select id="city" name="city" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="category">Kategori Rumah Sakit</label>
            <select id="category" name="category" class="form-control">
            </select>
        </div>
        <div class="form-group">
          <label for="Alamat">Alamat Rumah Sakit</label>
          <textarea type="text" required id="Alamat" name="Alamat" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="type">Tipe</label>
            <select id="type" name="type" class="form-control" required="">
      
         
            </select>
        </div>
        <div class="form-group">
            <label for="owner">Kepemilikan</label>
            <input type="" required  id="owner" name="owner" class="form-control">
        </div>
        <div class="form-group">
            <label for="class">Kelas</label>
            <select id="class" name="class" class="form-control" required="">
              
            </select>
        </div>
        <div class="form-group">
            <label for="akreditas">Akreditas</label>
            <select id="akreditas" name="akreditas" class="form-control" required="">
       
                   
                   
         
            </select>
        </div>
        <div class="form-group">
            <label for="target">Sasaran</label>
            <select id="target" name="target" class="form-control" required="">
                
               
                   
         
            </select>
        </div>
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update" id="btn-update">Update</button>
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
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/regencies.js"></script>

<script type="text/javascript">

  $(function () {
   
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('data.hospital') }}",
        columns: [
            {data: 'code' , name: 'code'},
            {data: 'name', name: 'name'},
            {data: 'provname', name: 'provname'},
            {data: 'prov_orderno', name: 'prov_orderno'},
            {data: 'category', name: 'category'},
            {data: 'city', name: 'city'},
            {data: 'address', name: 'address'},
            {data: 'ownership', name: 'ownership'},
            {data: 'owned_by', name: 'owned_by'},
            {data: 'class', name: 'class'},
            {data: 'akreditas', name: 'akreditas'},
            {data: 'target', name: 'target'},
           
            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });
  });

    // Reset Form
        function resetForm(){
            $("[name='name']").val("")
            $("[name='prov_order_no']").val("")
            $("[name='prov_region_code']").val("")
        }
    //

    // Create 
    // Create
    // Edit & Update
    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        var regency = citydata();
        var dataedit = hoseditdata();
        console.log(dataedit);
        $.ajax({
            url: "{{ route('admin.hospitaldetail', ['hospital' => ':id']) }}".replace(':id', id),
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show")
                $("#data").val(response.hospital.id);
                $("#name").val(response.hospital.name);
                $("#Alamat").val(response.hospital.address);
                $("#owner").val(response.hospital.ownership);
                var provinceSelect = $('#province');
                var $citySelect = $('#city');
                $citySelect.select2({width: '100%'});
                cityid=response.hospital.city_order_no.toString();
                provid=response.hospital.provselected.prov_region_code.toString();
                cats=response.hospital.category;
                types=response.hospital.owned_by;
                kelas=response.hospital.class;
                akreditasi=response.hospital.akreditas;
                target=response.hospital.target;
                console.log(kelas);
                provinceSelect.empty(); // Clear existing options
                 // Clear existing options
                $citySelect.empty(); // Clear existing options
                populateProvinceFromDatalist('province', response.provinceopt,"Pilih Provinsi");
                provinceSelect.prepend('<option value="' + provid + '" selected>' +response.hospital.provselected.name+ '</option>')
                
                var filteredCities = regency.filter(function(city) {
                        return city.province_id == provid;
                    });
               
               $.each(filteredCities, function(index, city) {
                if(cityid == city.id){
                  $citySelect.append('<option value="' + city.id + '"selected>' + city.name + '</option>');
                }else{
                   $citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                }
               });

               //popelate edit cat
               var catselect = $('#category');
               editHosPopulateSelect(catselect, dataedit.HosCat, cats, {width: '100%'});

               //populate type
               var typeselect = $('#type');
               editHosPopulateSelect(typeselect, dataedit.tipe, types, {width: '100%'});
               
               var kelasselect = $('#class');
               editHosPopulateSelect(kelasselect, dataedit.kelas, kelas, {width: '100%'});
              
               var akreditasselect = $('#akreditas');
               editHosPopulateSelect(akreditasselect, dataedit.akreditas, akreditasi, {width: '100%'});
              
               var targetselect = $('#target');
               editHosPopulateSelect(targetselect, dataedit.sasaran, target, {width: '100%'});
    
               
                //$citySelect.prepend('<option value="' + response.hospital.provselected.id + '" selected>' +response.hospital.provselected.name+ '</option>')
                $('#province').change(function() {
                    var provinceId = $(this).val();
                    //console.log(provinceId);
                    
                    $citySelect.empty(); // Clear existing options
                    $citySelect.select2({
                        placeholder: "pilih kota",
                        width: '100%' // Adjust the width to fit the container
                        });
                    // Filter cities based on the selected province
                    var filteredCities = regency.filter(function(city) {
                        return city.province_id == provinceId;
                    });
                    // Populate the city dropdown with the filtered cities
                    $.each(filteredCities, function(index, city) {
                        $citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                    });
                  
                });
                
            }
        })
    });


    $("#btn-update").on("click", function(e) {
    e.preventDefault();
    var hospital = $("#data").val();
    var formData = $("#editForm").serialize();
        var cityId = $("#city").val(); // Assuming you have a dropdown with the ID "city-dropdown"
        //var prov_reg_code = $("#province").val()
        var cityData = citydata().find(city => city.id === cityId);
        var provName = $("#province option:selected").text();
        console.log(provName);
        formData += "&cityname="+cityData.name;
        formData += "&provName="+provName;

    $.ajax({
        url: "{{ route('admin.hospitalupdate', ['hospital' => ':hospital']) }}".replace(':hospital', hospital),
        method: "PATCH",
        data: formData, // Serialize the form
        success: function(response) {
            // Display success message
            $("#edit-modal").modal("hide");
            $('.data-table').DataTable().ajax.reload();
            flash("success", "Data berhasil diupdate");
            $('.notify').focus();
        },
        error: function(xhr, status, error) {
            // Handle any errors that occurred during the request
            console.error('Error updating data:', error);
            flash("error", "An error occurred while updating the data.");
        }
    });
});



    //Edit & Update
/*
    $('body').on("click",".btn-delete",function(){
        var id = $(this).attr("id")
        $(".btn-destroy").attr("id",id)
        //$("#destroy-modal").modal("show")
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
*/
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