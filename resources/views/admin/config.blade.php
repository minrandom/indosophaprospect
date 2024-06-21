@extends('layout.backend.app',[
    'title' => 'Config',
    'pageTitle' =>'Config',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="notify"></div>

<div class="card">
    <div class="card-header">
        <!-- Button trigger modal -->
        <a type="button" class="btn btn-primary" href="{{ route('admin.configcreate')}}">
          Tambah Data
        </a>
    </div>
    
        <div class="card-body">
            <div class="table-responsive">    
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Config</th>
                            <th>Nama</th>
                            <th>Bussiness Unit</th>
                            <th>Kategori</th>
                            <th>Jenis</th>
                            <th>Brand</th>
                            <th>Tipe</th>
                            <th>UOM</th>
                            <th>Consist Of</th>
                            <th>Harga +PPn (IDR)</th>
                            <th>Action</th>
                         
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
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
        <input type="hidden" required="" id="data" name="data" class="form-control">
            <label for="name">Nama Config</label>
            <input type="" required placeholder="" id="name" name="name" class="form-control">
        </div>
        <div class="form-group">
            <label for="code">Kode Config</label>
            <input type="" required  id="code" name="code" class="form-control">
        </div>
        <div class="form-group">
            <label for="unit">Business Unit</label>
            <select id="unit" name="unit" class="form-control" required="">

            </select>
        </div>
      
        <div class="form-group">
            <label for="category">Product Category</label>
            <select id="category" name="category" class="form-control" required="">

            </select>
        </div>
        <div class="form-group">
            <label for="Jenis">Jenis</label>
            <select id="Jenis" name="Jenis" class="form-control" required="">
               
            </select>
        </div>
        <div class="form-group">
            <label for="brand">Brand</label>
            <select id="brand" name="brand" class="form-control">
              
            </select>
        </div>
        <div class="form-group">
          <label for="type">Tipe</label>
          <input type="" required placeholder="Input Tipe Alat" id="type" name="type" class="form-control" >
        </div>
        <div class="form-group">
            <label for="uom">UOM</label>
            <select id="uom" name="uom" class="form-control" required="">
                  
              
            </select>
        </div>
        <div class="form-group">
            <label for="consist">Consist Of</label>
            <input type="" id="consist" name="consist" class="form-control">
        </div>
        <div class="form-group">
        <label for="price">Harga</label>
            <input type="number" id="price" name="price" class="form-control">
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
<script type="text/javascript">


                         

  $(function () {
    
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('data.config') }}",
        columns: [
            {data: 'id' , name: 'id'},
            {data: 'config_code' , name: 'config_code'},
            {data: 'name' , name: 'name'},
            {data: 'unit.name' , name: 'bu'},
            {data: 'category.name' , name: 'category'},
            {data: 'genre' , name: 'genre'},
            {data: 'brand.name' , name: 'brand'},
            {data: 'type' , name: 'type'},
            {data: 'uom' , name: 'uom'},
            {data: 'consist_of' , name: 'consist_of'},
            {data: 'pformat' , name: 'pformat'},
   
           
            {data: 'action', name: 'action', orderable: false, searchable: true},
        ]
    });
  });



    // Create

    // Edit & Update
    $('body').on("click",".btn-edit",function(){
        var id = $(this).attr("id")
        var dataedit = configeditdata();
        $.ajax({
            url: "{{ route('admin.configdetail', ['config' => ':id']) }}".replace(':id', id),
            method: "GET",
            success:function(response){
                $("#edit-modal").modal("show");
                $("#data").val(response.config.id);
                
                $("#name").val(response.config.name);
                $("#code").val(response.config.config_code);
                $("#type").val(response.config.type);
                $("#consist").val(response.config.consist_of);
                $("#price").val(response.config.price_include_ppn);

                units = response.config.unit_id
                var unitselect = $('#unit');
                editConfPopulateSelect(unitselect, response.unit, units, {width: '100%'});

                brands = response.config.brand_id
                var brandselect = $('#brand');
                editConfPopulateSelect(brandselect, response.brand, brands, {width: '100%'});


                brands = response.config.brand_id
                var brandselect = $('#brand');
                editConfPopulateSelect(brandselect, response.brand, brands, {width: '100%'});

                genre= response.config.genre
                var Jenisselect = $('#Jenis');
                editHosPopulateSelect(Jenisselect, dataedit.jenis, genre, {width: '100%'});
                
                uom= response.config.uom
                var uomselect = $('#uom');
                editHosPopulateSelect(uomselect, dataedit.uom, uom, {width: '100%'});

                categoryData= response.categoryData;

                var filteredCats = categoryData.filter(function(cats) {
                        return cats.unit_id == units;
                    });
                    var catSelect = $('#category');
                
                    $.each(filteredCats, function(index, cats) {
                if(response.config.category_id == cats.id){
                  catSelect.append('<option value="' + cats.id + '"selected>' + cats.name + '</option>');
                }else{
                   catSelect.append('<option value="' + cats.id + '">' + cats.name + '</option>');
                }
               });
               catSelect.select2({width: '100%'});

               $('#unit').change(function() {
                    var unitId = $(this).val();
                    //console.log(provinceId);
                    
                    catSelect.empty(); // Clear existing options
                    catSelect.select2({
                        placeholder: "pilih category",
                        width: '100%' // Adjust the width to fit the container
                        });
                    // Filter cities based on the selected province
                    var filteredcat = categoryData.filter(function(cat) {
                        return cat.unit_id == unitId;
                    });
                    // Populate the city dropdown with the filtered cities
                    $.each(filteredcat, function(index, cate) {
                        catSelect.append('<option value="' + cate.id + '">' + cate.name + '</option>');
                    });
                  
                });
                
            }
        })
    });


  $("#btn-update").on("click", function(e) {
        e.preventDefault()
        var data= $("#data").val();
        console.log(data);
        var formData = $("#editForm").serialize();
        $.ajax({
            url: "{{ route('admin.configupdate', ['config' => ':config']) }}".replace(':config', data),
            method: "PATCH",
            data: formData,
            success:function(){
                $('.data-table').DataTable().ajax.reload();
                $("#edit-modal").modal("hide");
                flash("success","Data berhasil diupdate");
                $('.notify').focus();
            },
            error: function(xhr, status, error) {
            // Handle any errors that occurred during the request
            console.error('Error updating data:', error);
            flash("error", "An error occurred while updating the data.");
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
            url: "/admin/province/"+id,
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