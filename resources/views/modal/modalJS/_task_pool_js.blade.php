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


  let checklistMode = false;

  function toggleChecklist(show) {
    checklistMode = show;
    $('.js-col-check').toggleClass('d-none', !show);
    $('#btnShowChecklist').toggleClass('d-none', show);
    $('#btnHideChecklist').toggleClass('d-none', !show);
    $('#btnOpenAddToMissionRunModal').toggleClass('d-none', !show);

    if (!show) {
      $('.js-task-check').prop('checked', false);
    }
  }

  function getSelectedTasks() {
    const ids = [];
    const hospitals = new Set();

    $('.js-task-check:checked').each(function () {
      ids.push($(this).val());
      hospitals.add(String($(this).data('hospital-id') || '0'));
    });

    return { ids, hospitals: Array.from(hospitals) };
  }

  // --- Checklist buttons
  $('#btnShowChecklist').on('click', function () {
    toggleChecklist(true);
  });

  $('#btnHideChecklist').on('click', function () {
    toggleChecklist(false);
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

  // --- Open Create Mission modal
  $('#btnOpenCreateMissionRunModal').on('click', function () {
    // if filtered hospital exists, prefill

    if (window.selectedHospitalId) {
      $('#cmr_hospital_id').val(window.selectedHospitalId);
      //make it readonly by disabling select2 and select element

      $('#cmr_hospital_id').prop('disabled', true);
      $('#cmr_hospital_id').prop('readonly', true);
    }
    $('#createMissionRunModal').modal('show');
  });

  // Create mission run
  $('#btnCreateMissionRun').on('click', function () {
    const hospitalId = $('#cmr_hospital_id').val();
    const deadline = $('#cmr_deadline').val();
    const purpose = $('#cmr_purpose').val();
    const picUserId = $('#cmr_pic_user_id').val();

    if (!hospitalId) {
      Swal.fire({ icon:'warning', title:'Hospital required', text:'Please select hospital.' });
      return;
    }
    if (!deadline) {
      Swal.fire({ icon:'warning', title:'Deadline required', text:'Please select mission deadline.' });
      return;
    }

    Swal.fire({
      title: 'Create Mission?',
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Create',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: window.createMissionRunUrl,
        method: 'POST',
        data: {
          _token: window.csrfToken,
          hospital_id: hospitalId,
          deadline_mission: deadline,
          task_purpose: purpose,
          person_in_charge: picUserId
        },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Created', text: r.message || 'Mission created', timer: 1200, showConfirmButton:false });
          $('#createMissionRunModal').modal('hide');
          setTimeout(() => location.reload(), 700);
        },
        error: function (xhr) {
          console.error(xhr.responseText);
          Swal.fire({ icon:'error', title:'Failed', text:'Check server log.' });
        }
      });
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

  // Confirm add tasks to mission run
  $('#btnConfirmAddToMissionRun').on('click', function () {
    const { ids, hospitals } = getSelectedTasks();
    const missionRunId = $('#mission_run_id').val();

    if (!missionRunId) {
      Swal.fire({ icon:'warning', title:'Select mission', text:'Please choose mission first.' });
      return;
    }
    if (!ids.length) {
      Swal.fire({ icon:'warning', title:'No selected task', text:'Please check tasks again.' });
      return;
    }

    Swal.fire({
      title: 'Add tasks to mission?',
      html: `Selected: <b>${ids.length}</b> task(s)`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Add',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: window.bulkAddToMissionRunUrl,
        method: 'POST',
        data: {
          _token: window.csrfToken,
          mission_run_id: missionRunId,
          task_ids: ids,
          hospital_id: hospitals[0], // for safety validation server-side
        },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Done', text: r.message || 'Added', timer: 1200, showConfirmButton:false });
          $('#addToMissionRunModal').modal('hide');
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

  $('#btnClearFilter').on('click', function () {

  // reset dropdowns
  $('#province_id').val(0);
  $('#hospital_id').val(0);

  // reload page with clean query
  window.location.href = "/missions/task-pool";
});

});
</script>
