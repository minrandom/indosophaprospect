@extends('layout.backend.app', ['title' => 'Promo to Prospect'])

@section('content')
<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

  <div class="card shadow border-0 mb-4" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3"><b><span class="text-dark">Promo - Convert to Prospect</span></b></h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Task Reference:</b> {{ $task->task_reference}}</div>
      <div class="mb-2"><b>Task Source:</b> {{ $task->task_source_label }}</div>
      <div class="mb-2"><b>Prospect No:</b> {{ $prospect->prospect_no ?? 'Update into Prospect To Get Prospect Number' }}</div>
      <div class="mb-2"><b>Current Stage:</b> <span class="badge badge-info">{{ optional($prospect->temperature)->tempName ?? '-' }}</span></div>
      <div class="mb-2"><b>Prospect PIC:</b> {{ optional($prospect->personInCharge)->name ?? '-' }}</div>
      <div class="mb-2"><b>Hospital:</b> {{ optional($prospect->hospital)->name ?? '-' }}</div>
      <div class="mb-2"><b>Department:</b> {{ optional($prospect->department)->name ?? '-' }}</div>
      <div class="mb-2"><b>Business Unit:</b> {{ optional($prospect->unit)->name ?? '-' }}</div>
      <div class="mb-2"><b>Product:</b> {{ optional($prospect->config)->name ?? '-' }}</div>
    </div>
  </div>

  <form method="POST" action="{{ route('missions.task.lead.prospect.submit', $task->id) }}">
    @csrf

    <div class="card shadow border-0" style="border-radius:1rem;">
      <div class="card-body">
    <div id="productUpdateSection" >
        <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="h6 text-uppercase mb-0">Product Update</div>
        </div>
        <div class="row">
        <div class="col-md-4 mb-3">
            <label>Business Unit <span class="text-danger">*</span></label>
            <select name="unit_id" id="ltp_unit_id" class="form-control" >
            <option value="">Select Business Unit</option>

            </select>
        </div>
        <input type="hidden" name="prospect_id" value="{{ $prospect->id }}">
        <div class="col-md-4 mb-3">
            <label>Category <span class="text-danger">*</span></label>
            <select name="category_id" id="ltp_category_id" class="form-control" >
            <option value="">Select Category</option>

            </select>
        </div>

        <div class="col-md-4 mb-3">
            <label>Product / Config <span class="text-danger">*</span></label>
            <select name="config_id" id="ltp_config_id" class="form-control">
            <option value="">Select Product</option>

            </select>
        </div>


    </div>


        <div class="h6 text-uppercase mb-3">Review Data</div>

        <div class="row">
              <div class="col-md-6 mb-3">
                        <label>ETA PO Date<span class="text-danger">*</span></label>

                        <input type="date" name="eta_po_date" id="eta_po_date" class="form-control" required>
                    </div>
          <div class="col-md-6 mb-3">
                        <label>First Offer Date</label>

                        <input type="date" name="first_offer_date" id="first_offer_date" class="form-control"
                              >
                    </div>
                    <div class="col-md-6 mb-3">
                        <label>Demo Date</label>

                        <input type="date" name="demo_date" id="demo_date" class="form-control"
                              >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Presentation Date</label>
                        <input type="date" name="presentation_date" id="presentation_date" class="form-control"
                              >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Last Offer Date</label>
                        <input type="date" name="last_offer_date" id="last_offer_date" class="form-control"
                              >
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>User Status</label>
                        <select name="user_status" class="form-control" id="user_status">
                            <option value="">Select User Status</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Direksi Status</label>
                        <select name="direksi_status" class="form-control" id="direksi_status">
                            <option value="">Select Direksi Status</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Purchasing Status</label>
                        <select name="purchasing_status" class="form-control" id="purchasing_status">
                            <option value="">Select Purchasing Status</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Anggaran Status <span class="text-danger">*</span></label>
                        <select name="anggaran_status" class="form-control" id="anggaran_status">
                            <option value="">Select Anggaran Status</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Jenis Anggaran <span class="text-danger">*</span></label>
                        <select name="jenis_anggaran" class="form-control" id="jenis_anggaran" required>
                            <option value="">Select Jenis Anggaran</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Chance</label>
                        <select name="chance" class="form-control" id="chance">
                            <option value="">Select Chance</option>
                        </select>
                    </div>



                    <div class="col-md-12 mb-3">
                        <label>Comment <span class="text-danger">*</span></label>
                        <textarea name="comment" class="form-control" rows="3">{{ old('comment', optional($prospect->review)->comment) }}</textarea>
                    </div>


                    <div class="col-md-12 mb-3">
                        <label>Report Task <span class="text-danger">*</span></label>
                        <textarea name="report_result" class="form-control" rows="4" required>{{ old('report_result', $task->report_result) }}</textarea>
                    </div>
        </div>

      </div>

      <div class="card-footer bg-white text-right">
        <a href="{{ route('missions.runs.show', $task->mission_run_id) }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Submit for Validation</button>
      </div>
    </div>
  </form>
<div id="productData" data-url="{{ route('product.getProducts') }}"></div>
</div>
@endsection

@push('js')
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/jquery/jquery.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>


<script type="text/javascript">
$(document).ready(function() {

$('form').on('submit', function (e) {
    const unit = $('#ltp_unit_id').val();
    const config = $('#ltp_config_id').val();

    if (unit == '10' || config == '0') {
        e.preventDefault();
        swal.fire({
            icon: 'warning',
            title: 'Invalid Product Selection',
            text: 'Prospect must have valid Business Unit and Product.\n Please update the product information before submit. !',
        });
    }
});



 var id = $("input[name='prospect_id']").val();
 $('[data-toggle="tooltip"]').tooltip();
       $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {
            console.log(response);
          $("#data").val(response.prospect.id);


           var unitSelect = $("#ltp_unit_id");
                populateSelectFromDatalist('ltp_unit_id', response.bunit,"Pilih Business Unit");
                var catSelect=$("#ltp_category_id");

                function fetchcat(unitId) {
                  // Make an AJAX call to retrieve hospitals based on provinceId
                  $.ajax({
                    url: "{{ route('admin.getCategoriesByUnit', ['unitId' => ':unitId']) }}".replace(':unitId', unitId),
                    method: "GET",
                    success: function (response) {
                      populateSelectFromDatalist('ltp_category_id', response.catopt,"Pilih Kategori Produk");
                    }
                  });
                }

                  var productSelect = $("#ltp_config_id");
                unitSelect.on("change", function () {
                  var selectedunitId = $(this).val();
                  fetchcat(selectedunitId);
                });

                $("#ltp_unit_id, #ltp_category_id").on("change", function () {
                  var selectedBusinessUnitId = $("#ltp_unit_id").val();
                  var selectedCategoryId = $("#ltp_category_id").val();
                  var selectformId="ltp_config_id";

                  if (selectedBusinessUnitId && selectedCategoryId) {
                    populateProductSelect(selectedBusinessUnitId, selectedCategoryId,selectformId);
                  } else {


                    productSelect.empty();

                    productSelect.append('<option value="">- Pilih Produk -</option>');



                  }
                });


                catSelect.append($("<option>").val(response.proscat.id).text(response.proscat.name).attr('selected', 'selected'));
                unitSelect.append($("<option>").val(response.prospect.unit_id).text(response.prospect.unit.name).attr('selected', 'selected'));
                productSelect.append($("<option>").val(response.prospect.config_id).text(response.prospect.config.name).attr('selected', 'selected'));



          if(response.prospect.review.first_offer_date) {
              var firstOfferDateOnly = response.prospect.review.first_offer_date.split(' ')[0];
              $("#first_offer_date").val(firstOfferDateOnly);
          }


          if(response.prospect.review.last_offer_date) {
              var lastOfferDateOnly = response.prospect.review.last_offer_date.split(' ')[0];
              $("#last_offer_date").val(lastOfferDateOnly);
          }

          if(response.prospect.review.presentation_date) {
              var presentationDateOnly = response.prospect.review.presentation_date.split(' ')[0];
              $("#presentation_date").val(presentationDateOnly);
          }

          if(response.prospect.review.demo_date) {
              var demoDateOnly = response.prospect.review.demo_date.split(' ')[0];
              $("#demo_date").val(demoDateOnly);
          }

          var userStatusSelect = $("select[name='user_status']");
            userStatusSelect.empty();
            var optionuser = $("<option>").val(response.prospect.review.user_status).text(response.prospect.review.user_status);
            userStatusSelect.append(optionuser);

        response.sourceoption.state.forEach(function(usersts) {
            var option = $("<option>").val(usersts.name).text(usersts.name);
            userStatusSelect.append(option);
          });

        var purchasingSelect = $("#purchasing_status");
          purchasingSelect.empty();

          var optionpurchasing = $("<option>").val(response.prospect.review.purchasing_status).text(response.prospect.review.purchasing_status);
          purchasingSelect.append(optionpurchasing);

          response.sourceoption.state.forEach(function(purchasingsts) {
            var option = $("<option>").val(purchasingsts.name).text(purchasingsts.name);
            purchasingSelect.append(option);
          });

          var direksiSelect = $("#direksi_status");
          direksiSelect.empty();

          var optiondireksi = $("<option>").val(response.prospect.review.direksi_status).text(response.prospect.review.direksi_status);
          direksiSelect.append(optiondireksi);

          response.sourceoption.state.forEach(function(direksists) {
            var option = $("<option>").val(direksists.name).text(direksists.name);
            direksiSelect.append(option);
          });


          var anggaranSelect = $("#anggaran_status");
          anggaranSelect.empty();

          var option1 = $("<option>").val(response.prospect.review.anggaran_status).text(response.prospect.review.anggaran_status);
          anggaranSelect.append(option1);

          response.sourceoption.anggaran.review.forEach(function(anggaransts) {
            var option = $("<option>").val(anggaransts.name).text(anggaransts.name);
            anggaranSelect.append(option);
          });

          var jenisanggaranSelect = $("#jenis_anggaran");
          jenisanggaranSelect.empty();

          var optionjns = $("<option>").val(response.prospect.review.jenis_anggaran).text(response.prospect.review.jenis_anggaran);
          jenisanggaranSelect.append(optionjns);

          response.sourceoption.anggaran.Jenis.forEach(function(jenisanggaransts) {
            var option = $("<option>").val(jenisanggaransts.name).text(jenisanggaransts.name);
            jenisanggaranSelect.append(option);
          });


          // Use the ID selector and ensure we only take the date part (YYYY-MM-DD)
        if (response.prospect.eta_po_date) {
            var etaPoDateOnly = response.prospect.eta_po_date.split(' ')[0];
            $("#eta_po_date").val(etaPoDateOnly);
        }

        var chanceSelect = $("#chance");
          chanceSelect.empty();
          var chancenow = response.prospect.review.chance * 100;
        var optionchance = $("<option>").val(response.prospect.review.chance).text(chancenow + '%');
        chanceSelect.append(optionchance);

        response.sourceoption.chance.forEach(function(chance) {
            var option = $("<option>").val(chance.name).text(chance.name);
            chanceSelect.append(option);
          });

            var nextActionSelect = $("#next_action");
            nextActionSelect.empty();
            var optionnext = $("<option>").val(response.prospect.review.next_action).text(response.prospect.review.next_action);
            nextActionSelect.append(optionnext);

        response.sourceoption.naction.forEach(function(nextAction) {
            var option = $("<option>").val(nextAction.name).text(nextAction.name);
            nextActionSelect.append(option);
        });

        }
    });
});
</script>
