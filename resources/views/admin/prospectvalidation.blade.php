@extends('layout.backend.app',[
'title' => 'Approval Prospect',
'pageTitle' =>'Approval Prospect',
])

@push('css')
<link href="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

@endpush

@section('content')
<div class="notify"></div>

<div class="card">
  <div class="card-header">
    <!-- Button trigger modal -->
    <!--<a href="javascript:void(0)" id="{{ Auth::user()->name }}" class="btn btn-primary btn-sm ml-2 btn-cr8">Tambah Data</a>-->

    <!-- Button trigger modal 
        <button class="btn btn-primary btn-sm ml-2" type="button" data-toggle="collapse" data-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
        Filter
        </button>
-->

    <div class="collapse" id="filterCollapse">
      <div class="row mt-4">
        <div class="col-4">
          <div class="col col-lg-8">
            <div class="form-group">
              <label for="sumberinfofilter">Sumber Prospect :</label>
              <select id="sumberinfofilter" name="sumberinfofilter" class="form-control dropdown" required="">

              </select>
            </div>
          </div>
          <div class="col col-lg-8">
            <div class="form-group">
              <label for="tempefilter">Temperature :</label>
              <select id="tempefilter" name="tempefilter" class="form-control dropdown" required="">
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
              <select id="provincefilter" name="provincefilter" class="form-control dropdown" required="">

              </select>
            </div>
          </div>
          <div class="col col-lg-8">
            <div class="form-group">
              <label for="picfilter">PIC :</label>
              <select id="picfilter" name="picfilter" class="form-control dropdown" required="">

              </select>
            </div>
          </div>
        </div>

        <div class="col-4">
          <div class="col col-lg-8">
            <div class="form-group">
              <label for="BUfilter">Business Unit :</label>
              <select id="BUfilter" name="BUfilter" class="form-control dropdown" required="">

              </select>
            </div>
          </div>
          <div class="col col-lg-8">
            <div class="form-group">
              <label for="catfilter">Product Category :</label>
              <select id="catfilter" name="catfilter" class="form-control dropdown" required="">

              </select>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

  <div class="card-body">
    <div class="table-responsive drag">
      <table class="table table-bordered data-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Creator/ Submit Date</th>


            <th>Province/ AM</th>
            <th>Sumber Info</th>
            <th>PIC</th>
            <th>Rumah Sakit/ Departemen</th>


            <th>Produk yang ditawarkan</th>
            <th>Harga + PPn(IDR)</th>
            <th>Qty</th>
            <th>Anggaran</th>


            <th>Eta PO Date</th>
            <th>Status</th>


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
<!-- Modal Create -->

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
            <label for="bunit">Business Unit</label>
            <input readonly type="" required="" id="bunit" name="bunit" class="form-control">
          </div>

          <div class="form-group">
            <label for="product">Produk</label>
            <input readonly type="" required="" id="product" name="product" class="form-control">
          </div>
          <div class="form-group">
            <label for="etapodate">ETA PO DATE</label>
            <input readonly type="" required="" id="etapodate" name="etapodate" class="form-control">
          </div>



          <div class="form-group">
            <label for="quan">Quantity</label>
            <input readonly type="" required="false" id="quan" name="quan" class="form-control">
          </div>



          <div class="form-group">
            <label for="validation">Prospect Approval Status</label>

            <select required="" name="validation" id="validation" class="form-control">
              <option value="0">-APPROVE / REJECT-</option>
              <option value="1">APPROVE</option>
              <!--<option value="99">EXPIRED</option> -->
              <option value="404">REJECT</option>
              <!--<option value="0">NEW</option> -->
            </select>
          </div>


          <div class="form-group" style="display: none;" id="PIC">
            <label for="personincharge">PIC</label>
            <select required="" id="personincharge" name="personincharge" class="form-control">

            </select>
          </div>

          <div id="infoinput" style="display: none;" class="label label-warning"><b>Silahkan Input PIC</b></div>


          </br>
          </br>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update" id="validasibtn">Update</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Modal Validation -->

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
            <label for="submitdate">Tanggal Input</label>
            <input type="hidden" required="" id="data" name="data" class="form-control">

            <input readonly type="date" required="" id="submiteddate" name="submiteddate" class="form-control">
          </div>

          <div class="form-group">
            <label for="creatorname">Created By</label>
            <input readonly type="" required="" id="creatorname" name="creatorname" class="form-control">
          </div>

          <div class="form-group">
            <label for="sourceedit">Sumber Info</label>

            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="sourceedit" name="sourceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}" onchange="this.size=1" ;>

            </select>

          </div>

          <div class="form-group">
            <label for="provinceedit">Provinsi</label>
            <!--<input readonly type="" required="" id="provinceedit" name="provinceedit" class="form-control">-->
            <select id="provinceedit" name="provinceedit" class="form-control" required="" onmousedown="if(this.options.length>5){this.size=5;}" onchange="this.size=1" ;>

            </select>
          </div>
          <div class="form-group">
            <label for="hospitallist">Rumah Sakit</label>
            <select id="hospitallist" name="hospitallist" class="form-control" required="">
            </select>
          </div>

          <div class="form-group">
            <label for="departmentname">Departemen</label>
            <select required="" id="departmentname" name="departmentname" class="form-control">
            </select>
          </div>

          <div class="form-group">
            <label for="editunit">Business Unit</label>
            <select required="" id="editunit" name="editunit" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="editcategory">Category</label>
            <select required="" id="editcategory" name="editcategory" class="form-control">
            </select>
          </div>
          <div class="form-group">
            <label for="productlist">Produk</label>
            <select id="productlist" name="productlist" class="form-control" required="">

            </select>
          </div>
          <div class="form-group">
            <label for="qtyitem">Quantity</label>
            <input type="number" required="" id="qtyitem" name="qtyitem" class="form-control" min='1' oninput="validateQuantity()">
            <p id="quantityWarning" style="color: red; display: none;">Quantity Minimal angka 1</p>

          </div>

          <div class="form-group">
            <label for="anggaranedit">Anggaran</label>
            <select type="" required="" id="anggaranedit" name="anggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}" onchange="this.size=1" ;>
            </select>
          </div>

          <div class="form-group">
            <label for="jenisanggaranedit">JenisAnggaran</label>
            <select required="" id="jenisanggaranedit" name="jenisanggaranedit" class="form-control" onmousedown="if(this.options.length>5){this.size=5;}" onchange="this.size=1" ;>
            </select>
          </div>
          <div class="form-group">
            <label for="etapodateedit">ETA PO DATE</label>
            <input type="date" required="" id="etapodateedit" name="etapodateedit" class="form-control">

          </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary btn-update" id='updatebutton'>Update</button>
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
  $.ajax({
    url: "{{ route('fixmiss') }}",
    type: 'GET',
    success: function(response) {
      console.log("success");
    },
    error: function(xhr, status, error) {
      console.error('There was an error in temper update!', error);
    }
  });




  $(function() {

    var table = $('.data-table').DataTable({
      processing: true,
      serverSide: true,
      pageLength: 5,
      lengthMenu: [
        [5, 10, 20],
        [5, 10, 20]
      ],
      ajax: {
        url: "{{ route('data.prospect') }}",
        type: "POST",
        data: function(d) {
          d.status = 0;
          d.url = "prospectvalidation"
        }

      },

      columns: [{
          data: 'DT_RowIndex',
          name: 'rowindex'
        },
        {
          data: 'submitdate',
          name: 'submitdate'
        },
        {
          data: 'hospitaldata',
          name: 'province'
        },
        {
          data: 'prospect_source',
          name: 'prospect_source'
        },
        {
          data: 'personInCharge',
          name: 'personInCharge'
        },
        {
          data: 'hospitaldata',
          name: 'hospital'
        },
        //{data: 'department' , name: 'department'},
        //{data: 'unit' , name: 'unit'},

        {
          data: 'namaprod',
          name: 'namaprod',
          searchable: true
        },

        {
          data: 'price',
          name: 'price'
        },
        {
          data: 'qty',
          name: 'qty'
        },
        //{data: 'value' , name: 'value'},
        //{data: 'promotion' , name: 'promotion'},
        {
          data: 'anggaran',
          name: 'anggaran'
        },
        {
          data: 'etadate',
          name: 'etadate'
        },
        //{data: 'temperature' , name: 'temperature'},
        {
          data: 'statsname',
          name: 'statsname'
        },

        {
          data: 'action',
          name: 'action',
          orderable: false,
          searchable: true
        },
      ]
    });
  });

  // Create

  // Validtion & Update Validation
  $('body').on("click", ".btn-validasi", function() {
    var id = $(this).attr("id")

    $("#validation").change(function() {
      var selectedOption = $(this).val();
      if (selectedOption === "1") {
        $("#PIC").show();
        $("#infoinput").show();
      } else {
        $("#PIC").hide();
        $("#infoinput").hide();
      }
    });



    $.ajax({
      url: "{{ route('admin.prospectvalidation', ['prospect' => ':id']) }}".replace(':id', id),
      method: "GET",
      success: function(response) {
        $("#PIC").hide();
        $("#infoinput").hide();
        $("#validasi-modal").modal("show");
        $("#id").val(response.id);
        $("#provinces").val(response.province.name);
        $("#provcode").val(response.province.prov_order_no);
        $("#creator").val(response.creator.name);
        var submit = response.created_at.substr(0, 10);
        $("#submitdate").val(submit);
        $("#hospital").val(response.hospital.name);
        $("#department").val(response.department.name);
        $("#product").val(response.config.name);
        $("#bunit").val(response.unit.name);

        $("#etapodate").val(response.eta_po_date);
        $("#quan").val(response.qty);
        var picSelect = $("#personincharge");
        // Populate dropdown options
        picSelect.empty();
        response.piclist.forEach(function(pivc) {
          if (
            pivc.area == response.province.prov_order_no ||
            pivc.area === response.province.iss_area_code ||
            pivc.area === response.province.wilayah
          ) {
            var option = $("<option>").val(pivc.user_id).text(pivc.name);
            picSelect.append(option);
          }
        });

        picSelect.select2({

          width: '100%' // Adjust the width to fit the container
        });


        $("#validation").val(response.status).prop("selected", true);

      }
    })
  });

  $("#validasibtn").on("click", function(e) {
    e.preventDefault()

    // Display alert message to confirm submission
    if (confirm("Yakin Update Status Prospect ?")) {
      // Proceed with form submissionU

      submitValid(id);
      $("#validasi-modal").modal("hide");
      $('.data-table').DataTable().ajax.reload();
    } else {
      // Cancel form submission
      $("#validasi-modal").modal("hide");
      return false;
    }

  });
  //Validtion & Update Validation

  // function check is that any changes or not



  function submitValid() {
    var id = $("#id").val()
    var status = $("#validation").val()
    if (status == 404) {
      // Prompt the user to input the reason
      var reason = prompt("Input Alasan Mereject");
      if (reason === null) {
        // User clicked cancel, abort the submission
        return;
      }
      // Append the reason to the form data
      $("#validationForm").append('<input type="hidden" name="reason" value="' + reason + '">');
    }
    $.ajax({
      url: "{{ route('admin.prospectvalidationupdate', ['prospect' => ':id']) }}".replace(':id', id),
      method: "PATCH",
      data: $("#validationForm").serialize(),
      success: function(response) {
        $('.data-table').DataTable().ajax.reload();
        $("#validasi-modal").modal("hide");
        flash("success", response.message);
        $('.notify').focus()
      }
    })
  }


  // Edit & Update
  $('body').on("click", ".btn-edit", function() {
    var id = $(this).attr("id")

    $.ajax({
      url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
      method: "GET",
      success: function(response) {

        $("#edit-modal").modal("show");
        $("#data").val(response.prospect.id);
        $("#creatorname").val(response.prospect.creator.name);
        //$("#provinceedit").val(response.prospect.province.name);
        var sourceSelect = $("#sourceedit");
        populateSelectFromDatalist('sourceedit', response.sourceoption.source, "Pilih Sumber Informasi");
        response.event.forEach(function(event) {
          var option = $("<option>").val(event.id).text(event.name);
          sourceSelect.append(option);
        });


        var firstOptionsource = $("<option>").val(response.prospect.prospect_source).text(response.prospect.prospect_source).attr("selected", true);
        sourceSelect.prepend(firstOptionsource);

        var provinceSelect = $("#provinceedit");

        var firstOption = $("<option>").val(response.prospect.province_id).text(response.prospect.province.name).attr("selected", true);
        provinceSelect.prepend(firstOption);
        provinceSelect.select2({

          width: '100%' // Adjust the width to fit the container
        });

        var submit = response.prospect.created_at.substr(0, 10);
        $("#submiteddate").val(submit);
        $("#hospitalname").val(response.prospect.hospital.name);
        //var hosinput=$("#hospitalname");
        var hospitalSelect = $("#hospitallist");
        //provinceSelect.empty(); // Clear existing options



        populateSelectFromDatalist('hospitallist', response.hosopt, "Pilih Rumah Sakit");
        var firstHospital = $("<option>").val(response.prospect.hospital_id).text(response.prospect.hospital.name).attr('selected', true);
        hospitalSelect.prepend(firstHospital);
        // Populate dropdown options
        function fetchHospitals(provinceId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getHospitalsByProvince', ['provinceId' => ':provinceId']) }}".replace(':provinceId', provinceId),
            method: "GET",
            success: function(response) {

              populateSelectFromDatalist('hospitallist', response.hosopt, "Pilih Rumah Sakit");

            }
          });
        }




        provinceSelect.on("change", function() {
          var selectedProvinceId = $(this).val();
          fetchHospitals(selectedProvinceId);
        });



        //$("#departmentname").val(response.prospect.department.name);
        var deptSelect = $("#departmentname");
        populateSelectFromDatalist('departmentname', response.depopt, "Pilih Departemen");
        var firstdept = $("<option>").val(response.prospect.department_id).text(response.prospect.department.name).attr("selected", true);
        deptSelect.prepend(firstdept);


        var unitselect = $("#editunit");
        populateSelectFromDatalist('editunit', response.bunit, "Pilih Business Unit");
        var firstunit = $("<option>").val(response.prospect.unit_id).text(response.prospect.unit.name).attr("selected", true);
        unitselect.prepend(firstunit);

        var catSelect = $("#editcategory");
        var idcat = response.prospect.config.category_id;
        populateSelectFromDatalist('editcategory', response.catopt, "Pilih Kategori Produk");

        function catdata(unitId) {
          // Make an AJAX call to retrieve the category name based on unitId
          $.ajax({
            url: "{{ route('admin.getCatname', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
            method: "GET",
            success: function(response) {
              var catname = response.catdat.name;
              var catid = response.catdat.id;

              // console.log(catname);
              // Call the callback function with the retrieved category name
              var ncatSelect = $("#editcategory");
              // For example, update an input field with the category name
              var firstCategory = $("<option>").val(catid).text(catname).attr('selected', true);
              ncatSelect.append(firstCategory);

            },

          });
        }

        catdata(idcat);

        function fetchcat2(unitId) {
          // Make an AJAX call to retrieve hospitals based on provinceId
          $.ajax({
            url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
            method: "GET",
            success: function(response) {
              populateSelectFromDatalist('editcategory', response.catopt, "Pilih Kategori Produk");
            }
          });
        }

        unitselect.on("change", function() {
          var selectedunitId = $(this).val();
          fetchcat2(selectedunitId);
        });

        var prodSelect = $("#productlist");
        prodSelect.empty();
        populateSelectFromDatalist('productlist', response.configlist, "Pilih Produk");
        var firstconf = $("<option>").val(response.prospect.config_id).text(response.prospect.config.name).attr('selected', true);
        prodSelect.prepend(firstconf);

        $("#editunit, #editcategory").on("change", function() {
          var selectedBusinessUnitId = $("#editunit").val();
          var selectedCategoryId = $("#editcategory").val();
          var selectformId = "productlist";

          if (selectedBusinessUnitId && selectedCategoryId) {
            populateProductSelect(selectedBusinessUnitId, selectedCategoryId, selectformId);
          } else {

            var productSelect = $("#productlist");
            productSelect.empty();
            productSelect.append('<option value="">- Pilih Produk -</option>');
          }
        });







        $("#qtyitem").val(response.prospect.qty);


        var anggaranSelect = $("#anggaranedit");
        anggaranSelect.empty();
        populateSelectFromDatalist('anggaranedit', response.sourceoption.anggaran.review, "Pilih Review Anggaran");
        var firstOptionanggaran = $("<option>").val(response.prospect.review.anggaran_status).text(response.prospect.review.anggaran_status).attr('selected', true);
        anggaranSelect.prepend(firstOptionanggaran);

        var anggartpSelect = $("#jenisanggaranedit");
        anggartpSelect.empty();
        populateSelectFromDatalist('jenisanggaranedit', response.sourceoption.anggaran.Jenis, "Pilih JenisAnggaran");
        var firstOptionanggarantp = $("<option>").val(response.prospect.review.jenis_anggaran).text(response.prospect.review.jenis_anggaran).attr('selected', true);
        anggartpSelect.prepend(firstOptionanggarantp);

        $("#etapodateedit").val(response.prospect.eta_po_date);
        var today = new Date();

        // Calculate the date after 30 days from today
        var thirtyDaysFromToday = new Date(today);
        thirtyDaysFromToday.setDate(today.getDate() + 30);

        var todayFormatted = today.toISOString().split('T')[0];
        var thirtyDaysFromTodayFormatted = thirtyDaysFromToday.toISOString().split('T')[0];
        $("#etapodateedit").attr('min', thirtyDaysFromTodayFormatted)



      }
    })
  });

  $("#updatebutton").on("click", function(e) {
    e.preventDefault()
    var prospect = $("#data").val()

    $.ajax({
      url: "{{ route('admin.prospectupdate', ['prospect' => ':prospect']) }}".replace(':prospect', prospect),
      method: "PATCH",
      data: $(this).serialize(),
      success: function() {
        $('.data-table').DataTable().ajax.reload();
        $("#edit-modal").modal("hide");
        flash("success", "Data berhasil diupdate");
        $('.notify').focus()

      }
    })
  })
  //Edit & Update




  //delete

  $('body').on("click", ".btn-delete", function() {
    var id = $(this).attr("id")
    $(".btn-destroy").attr("id", id)
    $("#destroy-modal").modal("show")
  });

  $(".btn-destroy").on("click", function() {
    var id = $(this).attr("id")

    $.ajax({
      url: "/admin/province/" + id,
      method: "DELETE",
      success: function() {
        $("#destroy-modal").modal("hide");
        $('.data-table').DataTable().ajax.reload();
        flash('success', 'Data berhasil dihapus');

      }
    });
  })
</script>
@endpush