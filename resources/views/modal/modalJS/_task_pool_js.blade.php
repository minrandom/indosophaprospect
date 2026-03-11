<script>
$(function () {

 function reloadMissionRuns(hospitalId) {
    // return jqXHR (Promise-like)
    return $.ajax({
      url: window.missionRunsByHospitalUrl, // set this in blade
      method: 'GET',
      data: { hospital_id: hospitalId }
    }).done(function (res) {
        console.log(typeof res, res);
      // res example: [{id, code}, ...]
      const $sel = $('#mission_run_id');

      $sel.empty().append(new Option('Select Mission', '', true, true));



        const runs = res.data;

        console.log('Loaded missions for hospital', hospitalId, runs);
      runs.forEach(function (m) {
        $sel.append(new Option(`${m.code} - ${m.hospital.name}`, m.id, false, false));
      });

      // if using select2
      $sel.trigger('change');
    }).fail(function (xhr) {
      console.error(xhr.responseText);
      Swal.fire({ icon:'error', title:'Failed', text:'Cannot load mission list.' });
    });
  }



  $('#btnHideTaskList').on('click', function () {
     $('#taskAccordionSection').addClass('d-none');
     $('#btnHideTaskList').addClass('d-none');
     $('#btnShowTaskList').removeClass('d-none');
     $('#calendarSection').removeClass('col-lg-5').addClass('col-lg-12');
  });

  $('#btnShowTaskList').on('click', function () {
     $('#taskAccordionSection').removeClass('d-none');
     $('#btnHideTaskList').removeClass('d-none');
     $('#btnShowTaskList').addClass('d-none');
    $('#calendarSection').removeClass('col-lg-12').addClass('col-lg-5');
  });

    $('#btnHideCalendar').on('click', function () {
        $('#calendarSection').addClass('d-none');
        $('#btnHideCalendar').addClass('d-none');
        $('#btnShowCalendar').removeClass('d-none');
        $('#taskAccordionSection').removeClass('col-lg-7').addClass('col-lg-12');
    });

    $('#btnShowCalendar').on('click', function () {
      $('#calendarSection').removeClass('d-none');
      $('#btnHideCalendar').removeClass('d-none');
      $('#btnShowCalendar').addClass('d-none');
      $('#taskAccordionSection').removeClass('col-lg-12').addClass('col-lg-7');
      });


  // Enforce SAME hospital only
  $(document).on('change', '.js-task-check', function () {
    const { hospitals } = getSelectedTasks();

    if (hospitals.length <= 1) return;

    // revert current check
    $(this).prop('checked', false);

    Swal.fire({
      icon: 'warning',
      title: 'Different hospital detected',
      text: 'Tasks in 1 mission must be from the same hospital.'
    });
  });




  // --- Open Add-to-mission modal
  $('#btnOpenAddToMissionRunModal').on('click', function () {
     const { ids, hospitals } = getSelectedTasks();

    if (!ids.length) {
      Swal.fire({ icon:'warning', title:'No selected task', text:'Please check at least 1 task.' });
      return;
    }

    if (hospitals.length !== 1) {
      Swal.fire({ icon:'warning', title:'Hospital mismatch', text:'Selected tasks must be from same hospital.' });
      return;
    }

    const hospitalId = hospitals[0]; // ✅ the only hospital id

    $('#am_count').text(ids.length);

    // store selected ids to hidden input so modal submit can use it
    $('#am_task_ids').val(ids.join(','));

    // ✅ reload first, show modal after loaded
    reloadMissionRuns(hospitalId).done(function () {
      $('#addToMissionRunModal').modal('show');
    });
  });


  $('#btnClearFilter').on('click', function () {

  // reset dropdowns
  $('#province_id').val(0);
  $('#hospital_id').val(0);

  // reload page with clean query
  window.location.href = "/missions/task-pool";
});

});
</script>


<script>

let requestMode = false;
let activeHospital = null;

$('.btn-setup-visit').on('click', function (e) {
  e.preventDefault();
  e.stopPropagation();

  const hospitalId = $(this).data('hospital-id');
  const $header = $(this).closest('.card-header');
  const $card = $(this).closest('.card');
  const $collapse = $card.find('.collapse').first();

  requestMode = true;
  activeHospital = hospitalId;

  // open current accordion
  $collapse.collapse('show');

  // hide all check columns first
  $('.js-col-check').addClass('d-none');
  $('.js-task-check').prop('checked', false);

  // show only this hospital checkboxes
  $('.js-task-check').each(function () {
    const rowHospital = $(this).data('hospital-id');

    if (String(rowHospital) === String(hospitalId)) {
      $(this).closest('td').removeClass('d-none');
    } else {
      $(this).closest('td').addClass('d-none');
    }
  });

  // reset all accordion buttons first
  $('.btn-plan-visit').addClass('d-none');
  $('.btn-hide-visit').addClass('d-none');
  $('.btn-setup-visit').removeClass('d-none');


  $card.find('.js-col-check').removeClass('d-none');
  // show only current header buttons
  $header.find('.btn-setup-visit').addClass('d-none');
  $header.find('.btn-plan-visit').removeClass('d-none');
  $header.find('.btn-hide-visit').removeClass('d-none');
});

$('.btn-hide-visit').on('click', function () {
  const $header = $(this).closest('.card-header');
    const $card = $(this).closest('.card');


  requestMode = false;
  activeHospital = null;

  $card.find('.js-task-check').prop('checked', false);
  $card.find('.js-col-check').addClass('d-none');

  // only reset current header
  $header.find('.btn-plan-visit').addClass('d-none');
  $header.find('.btn-hide-visit').addClass('d-none');
  $header.find('.btn-setup-visit').removeClass('d-none');
});


</script>
