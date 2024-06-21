@extends('layout.backend.app',[
    'title' => 'Tambah RS',
    'pageTitle' =>'Tambah Data Rumah Sakit',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
        .tooltip-inner {
            max-width: 300px; /* Set the maximum width of the tooltip */
            width: auto; /* Allow the tooltip to expand horizontally if needed */
        }
  </style>



@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    
        <div class="card-body">
           
        <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tambah Data Rumah Sakit</div>

                <div class="card-body">
                  <form id="createForm">
                        @csrf

                  <div class="form-group">
            <label for="name">Nama Rumah Sakit</label>
            <input type="" required placeholder="ABCDE , RS" id="name" name="name" class="form-control">
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
                <option val=''>Pilih Kategori</option>
                <option val='SMALL'>SMALL</option>
                <option val='MEDIUM'>MEDIUM</option>
                <option val='LARGE'>LARGE</option>
                <option val='MAJOR'>MAJOR</option>
            </select>
        </div>
        <div class="form-group">
          <label for="Alamat">Alamat Rumah Sakit</label>
          <textarea type="text" required id="Alamat" name="Alamat" class="form-control" rows="4"></textarea>
        </div>
        <div class="form-group">
            <label for="type">Tipe</label>
            <select id="type" name="type" class="form-control" required="">
                    <option val=''>Pilih Tipe</option> 
                    <option val='Swasta'>Swasta</option>
                    <option val='Swasta-Group'>Swasta-Group</option>
                    <option val='Kementerian Kesehatan'>Kementerian Kesehatan</option>
                    <option val='TNI / POLRI'>TNI / POLRI</option>
                    <option val='Yayasan / Organisasi'>Yayasan / Organisasi</option>
                    <option val='Pemerintah'>Pemerintah</option>
                    <option val='Pendidikan'>Pendidikan</option>
                    <option val='Kementerian Lain'>Kementerian Lain</option>
                    <option val='Negeri'>Negeri</option>
         
            </select>
        </div>
        <div class="form-group">
            <label for="owner">Kepemilikan</label>
            <input type="" required  id="owner" name="owner" class="form-control">
        </div>
        <div class="form-group">
            <label for="class">Kelas</label>
            <select id="class" name="class" class="form-control" required="">
                    <option val=''>Pilih Kelas</option>
                    <option val='A'>A</option>
                    <option val='B'>B</option>
                    <option val='C'>C</option>
                    <option val='D'>D</option>
            </select>
        </div>
        <div class="form-group">
            <label for="akreditas">Akreditas</label>
            <select id="akreditas" name="akreditas" class="form-control" required="">
                    <option val='Belum Ditetapkan'>Belum Ditetapkan</option> 
                    <option val='Tingkat Paripurna'>Tingkat Paripurna</option>
                    <option val='Tingkat Utama'>Tingkat Utama</option>
                    <option val='Lulus Perdana'>Lulus Perdana</option>
                    <option val='Tingkat Madya'>Tingkat Madya</option>
                    <option val='Tingkat Dasar'>Tingkat Dasar</option>
                  
                   
         
            </select>
        </div>
        <div class="form-group">
            <label for="target">Sasaran</label>
            <select id="target" name="target" class="form-control" required="">
                    <option val='Need Review'>Need Review</option> 
                    <option val='Potensial'>Potensial</option>
                    <option val='Key Account'>Key Account</option>
                    <option val='Prioritas'>Prioritas</option>
               
                   
         
            </select>
        </div>
      </br>
    </br>
  </div>
  <div class="modal-footer">
        <!--<button type="submit" class="btn btn-info btn-draft" id="btn-draft" >Simpan Draft</button>-->
        <button type="submit" class="btn btn-primary btn-store" id="btn-store" >Tambah Data</button>
        </form>
      </div>
            </div>
        </div>
    </div>
</div>


          </div>

    
</div>
<div id="productData" data-url="{{ route('product.getProducts') }}"></div>
<!-- Modal Create -->
<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-labelledby="create-modalLabel" aria-hidden="true">
</div>
<!-- Modal Create -->

@stop

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/datatables-demo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/regencies.js"></script>
<script type="text/javascript">


                         

  $(function () {
    var regency = citydata();
    console.log(regency);
  
 var id = $(this).attr("id")
 $('[data-toggle="tooltip"]').tooltip(); 
        $.ajax({
            url:"{{ route('admin.createhosdata') }}",
            method: 'GET',
            success: function(data) {
                var $provinceSelect = $('#province');
                $provinceSelect.empty(); // Clear existing options
                populateProvinceFromDatalist('province', data.prov,"Pilih Provinsi");

                $('#province').change(function() {
                    
                    var provinceId = $(this).val();
                    console.log(provinceId);
                    var $citySelect = $('#city');
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
                
             
                $('#category').change(function(){
                    var cityId = $(this).val();
                    console.log(cityId);
                });

                $catselect = $("#category");
                $catselect.select2({
                        placeholder: "pilih kategori",
                        width: '100%' // Adjust the width to fit the container
                });


            }
        })
    });


    // Create 
    function submitForm(url, successMessage) {
        var formData = $("#createForm").serialize();
        var cityId = $("#city").val(); // Assuming you have a dropdown with the ID "city-dropdown"
        //var prov_reg_code = $("#province").val()
        var cityData = citydata().find(city => city.id === cityId);
        formData += "&cityname="+cityData.name;
        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            success: function() {
                //$("#create-modal").modal("hide");
                //$('.data-table').DataTable().ajax.reload();
                $("#createForm")[0].reset();
                flash("success", successMessage);
                document.querySelector(".notify").scrollIntoView({
                behavior: "smooth",
                block: "start"
                });
            }
        });
    };



    $('#createForm').on('submit', function(e) {
        e.preventDefault();
        var clickedButton = $(document.activeElement);

        if (clickedButton.is('#btn-store')) {
            submitForm("{{ route('admin.hospitalstore') }}", "Data Rumah Sakit Berhasil Ditambah");
        } 
    });

    
  



    //delete

    // $('body').on("click",".btn-delete",function(){
    //     var id = $(this).attr("id")
    //     $(".btn-destroy").attr("id",id)
    //     $("#destroy-modal").modal("show")
    // });

    // $(".btn-destroy").on("click",function(){
    //     var id = $(this).attr("id")

    //     $.ajax({
    //         url: "/admin/province/"+id,
    //         method: "DELETE",
    //         success:function(){
    //             $("#destroy-modal").modal("hide")
    //             $('.data-table').DataTable().ajax.reload();
    //             flash('success','Data berhasil dihapus')
    //         }
    //     });
    // })

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