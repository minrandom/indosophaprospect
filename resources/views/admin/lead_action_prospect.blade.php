@extends('layout.backend.app', ['title' => 'Lead to Prospect'])

@section('content')
<div class="container-fluid">

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="card shadow border-0 mb-4" style="border-radius:1rem;">
    <div class="card-body">
      <h4 class="mb-3">Lead Action - Convert to Prospect</h4>

      <div class="mb-2"><b>Task Code:</b> {{ $task->code }}</div>
      <div class="mb-2"><b>Current Stage:</b> {{ optional($prospect->latestTemperature)->tempName ?? '-' }}</div>
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

        <div class="h6 text-uppercase mb-3">Review Data</div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label>First Offer Date</label>
            <input type="date" name="first_offer_date" class="form-control"
                   value="{{ old('first_offer_date', optional($prospect->review)->first_offer_date) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Demo Date</label>
            <input type="date" name="demo_date" class="form-control"
                   value="{{ old('demo_date', optional($prospect->review)->demo_date) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Presentation Date</label>
            <input type="date" name="presentation_date" class="form-control"
                   value="{{ old('presentation_date', optional($prospect->review)->presentation_date) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Last Offer Date</label>
            <input type="date" name="last_offer_date" class="form-control"
                   value="{{ old('last_offer_date', optional($prospect->review)->last_offer_date) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>User Status</label>
            <input type="text" name="user_status" class="form-control"
                   value="{{ old('user_status', optional($prospect->review)->user_status) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Direksi Status</label>
            <input type="text" name="direksi_status" class="form-control"
                   value="{{ old('direksi_status', optional($prospect->review)->direksi_status) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Purchasing Status</label>
            <input type="text" name="purchasing_status" class="form-control"
                   value="{{ old('purchasing_status', optional($prospect->review)->purchasing_status) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Anggaran Status</label>
            <input type="text" name="anggaran_status" class="form-control" required
                   value="{{ old('anggaran_status', optional($prospect->review)->anggaran_status) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Jenis Anggaran</label>
            <input type="text" name="jenis_anggaran" class="form-control" required
                   value="{{ old('jenis_anggaran', optional($prospect->review)->jenis_anggaran) }}">
          </div>

          <div class="col-md-6 mb-3">
            <label>Chance (%)</label>
            <input type="number" step="0.01" name="chance" class="form-control" required
                   value="{{ old('chance', optional($prospect->review)->chance ?? 20) }}">
          </div>

          <div class="col-md-12 mb-3">
            <label>Comment</label>
            <textarea name="comment" class="form-control" rows="3">{{ old('comment', optional($prospect->review)->comment) }}</textarea>
          </div>

          <div class="col-md-12 mb-3">
            <label>Next Action</label>
            <textarea name="next_action" class="form-control" rows="3" required>{{ old('next_action', optional($prospect->review)->next_action) }}</textarea>
          </div>

          <div class="col-md-12 mb-3">
            <label>Report Task</label>
            <textarea name="report_result" class="form-control" rows="4" required>{{ old('report_result') }}</textarea>
          </div>
        </div>

      </div>

      <div class="card-footer bg-white text-right">
        <a href="{{ route('missions.runs.show', $task->mission_run_id) }}" class="btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-primary">Submit for Validation</button>
      </div>
    </div>
  </form>

</div>
@endsection

@push('js')
<script src="{{ asset('template/backend/sb-admin-2') }}/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="{{ asset('template/backend/sb-admin-2') }}/js/demo/functionjojo.js"></script>
<script src="{{ asset('template/backend/sb-admin-2')}}/vendor/sweetalert/sweetalert.all.js"></script>
<script>
$(document).ready(function() {
 var id = $("input[name='prospect_id']").val();
 $('[data-toggle="tooltip"]').tooltip();
       $.ajax({
        url: "{{ route('admin.prospectedit', ['prospect' => ':id']) }}".replace(':id', id),
        method: "GET",
        success: function(response) {
            console.log(response);
          $("#data").val(response.prospect.id);

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
