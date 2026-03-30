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


<script>
$(function () {


  function getSelectedTaskIdsByHospital(hospitalId, $card) {
    return $card.find('.js-task-check:checked').map(function () {
      return $(this).val();
    }).get();
  }

  $('.btn-plan-visit').on('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    const hospitalId = $(this).data('hospital-id');
    const hospitalName = $(this).data('hospital-name');
    const $card = $(this).closest('.card');

    const ids = getSelectedTaskIdsByHospital(hospitalId, $card);

    if (!ids.length) {
      Swal.fire({
        icon: 'warning',
        title: 'No selected task',
        text: 'Please check at least 1 task before planning visit.'
      });
      return;
    }

    $('#pv_hospital_id').val(hospitalId);
    $('#pv_task_ids').val(ids.join(','));
    $('#pv_hospital_name').text(hospitalName || '-');
    $('#pv_task_count').text(ids.length);

        loadPicOptions(hospitalId);


    $('#planVisitModal').modal('show');
  });

  $('#btnSubmitPlanVisit').on('click', function () {


    const hospitalId = $('#pv_hospital_id').val();
    const taskIds = ($('#pv_task_ids').val() || '').split(',').filter(Boolean);
    const scheduledAt = $('#pv_scheduled_at').val();
    const scheduleTime = $('#pv_schedule_time').val();
    const scheduleDuration = $('#pv_schedule_duration').val();
    const picUserId = $('#pv_pic_user_id').length ? $('#pv_pic_user_id').val() : null;

    if (!scheduledAt) {
      Swal.fire({
        icon: 'warning',
        title: 'Schedule required',
        text: 'Please select a scheduled date.'
      });
      return;
    }

    if (!scheduleTime) {
        Swal.fire({
        icon: 'warning',
        title: 'Schedule time required',
        text: 'Please select schedule time.'
        });
        return;
    }
    if (!scheduleDuration) {
        Swal.fire({
        icon: 'warning',
        title: 'Duration required',
        text: 'Please select duration.'
        });
        return;
    }

    if (!window.isFs) {
        if (!picUserId) {
            Swal.fire({
                icon: 'warning',
                title: 'PIC required',
                text: 'Please select PIC.'
            });
            return;
        }
    }

    Swal.fire({
      title: 'Create Visit?',
      html: `Selected tasks: <b>${taskIds.length}</b>
      Date: <b>${scheduledAt}</b><br>
      Time: <b>${scheduleTime}</b>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Create',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: "{{ route('missionRuns.planVisit') }}",
        method: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          hospital_id: hospitalId,
          task_ids: taskIds,
          schedule_date: scheduledAt,
          schedule_time: scheduleTime,
          schedule_duration_minutes: scheduleDuration,
          pic_user_id: picUserId
        },
        success: function (r) {
          Swal.fire({
            icon: 'success',
            title: 'Visit Created',
            text: r.message || 'Visit created successfully.',
            timer: 1200,
            showConfirmButton: false
          });

          $('#planVisitModal').modal('hide');
          setTimeout(() => location.reload(), 700);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          let msg = 'Failed. Check server log.';
          try {
            const json = JSON.parse(xhr.responseText);
            if (json.message) msg = json.message;
          } catch(e) {}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

});


function loadPicOptions(hospitalId) {
  const $pic = $('#pv_pic_user_id');

  if (!$pic.length) return; // FS modal may not have PIC select

  $pic.empty().append('<option value="">Loading PIC...</option>');

  $.ajax({
    url: window.planVisitPicUrl.replace('__HOSPITAL__', hospitalId),
    method: 'GET',
    dataType: 'json',
    success: function (res) {
      const rows = Array.isArray(res) ? res : (res.data || []);

      $pic.empty().append('<option value="">Select PIC</option>');

      rows.forEach(function (u) {
        $pic.append(
          $('<option>', {
            value: u.id,
            text: u.label
          })
        );
      });

      if (!rows.length) {
        $pic.append('<option value="">No PIC found</option>');
      }
    },
    error: function (xhr) {
      console.error(xhr.responseText);
      $pic.empty().append('<option value="">Failed load PIC</option>');
    }
  });
}



</script>
