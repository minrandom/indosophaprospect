@extends('layout.backend.app',[
    'title' => 'Tambah Config',
    'pageTitle' =>'Tambah Data Config',
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
                <div class="card-header">Tambah Data Config</div>

                <div class="card-body">
                  <form id="createForm">
                        @csrf

                  <div class="form-group">
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
                    <option val='Unit'>Unit</option> 
                    <option val='Accessories'>Accesories</option>
                    <option val='Accessories/ Instrument'>Accesories/ Instrument</option>
                    <option val='Consumables'>Consumables</option>
                    <option val='Cst Package'>Cst Package</option>
                    <option val='Option'>Option</option>
                    <option val='Option/Accessories'>Option/Accessories</option>
                    <option val='Package'>Package</option>
                    <option val='Set'>Set</option>
                    <option val='Spareparts'>Spareparts</option>
              
               
            </select>
        </div>
        <div class="form-group">
            <label for="brand">Brand</label>
            <select id="brand" name="brand" class="form-control">
              
            </select>
        </div>
        <div class="form-group">
          <label for="tipe">Tipe</label>
          <input type="" required placeholder="Input Tipe Alat" id="tipe" name="tipe" class="form-control" >
        </div>
        <div class="form-group">
            <label for="uom">UOM</label>
            <select id="uom" name="uom" class="form-control" required="">
                    <option val='Unit'>Unit</option> 
                    <option val='Shock'>Shock</option>
                    <option val='Package'>Package</option>
                    <option val='Set'>Set</option>
                    <option val='Pcs'>Pcs</option>
                    <option val='Pack'>Pack</option>
                    <option val='Box'>Box</option>
              
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
            url:"{{ route('admin.createconfdata') }}",
            method: 'GET',
            success: function(data) {
                var unitSelect = $('#unit');
                unitSelect.empty(); // Clear existing options
                populateSelectFromDatalist('unit', data.unit,"Pilih Sumber Informasi");

                function fetchcat(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('category', response.catopt,"Pilih Kategori Produk");
                    }
                  });
                }
             
                unitSelect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat(selectedunitId);
                });
                Jenisselect= $("#Jenis");
                Jenisselect.select2({
                        placeholder: "Pilih Jenis Item",
                        width: '100%' // Adjust the width to fit the container
                });

                populateSelectFromDatalist('brand', data.brand,"Pilih Brand Principle");


            }
        })
    });


    // Create 
    function submitForm(url, successMessage) {
        var formData = $("#createForm").serialize();
   
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
            submitForm("{{ route('admin.configstore') }}", "Data Config Berhasil Ditambah");
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