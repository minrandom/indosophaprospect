<script>
$(function () {

  function openModalWith(mission, date, time) {
    $('#sm_mission_id').val(mission.id);
    $('#sm_mission_code').text(mission.code || '-');
    $('#sm_hospital').text(mission.hospital || '-');
    $('#sm_pic').text(mission.pic || '-');
    $('#sm_date').val(date);
    $('#sm_time').val(time);
    $('#sm_date_input').val(date);
    $('#sm_time_input').val(time);
    $('#sm_duration').val('30');
    $('#scheduleModal').modal('show');
  }

  // Click "+" from mission list
//   $(document).on('click', '.js-open-schedule', function () {
//     const mission = {
//       id: $(this).data('mission-id'),
//       code: $(this).data('mission-code'),
//       hospital: $(this).data('hospital'),
//       pic: $(this).data('pic'),
//     };

//     // default = today cell (optional), here use weekStart Monday + 08:00
//     const firstDate = "{{ $weekStart->toDateString() }}";
//     openModalWith(mission, firstDate, '08:00');
//   });

//   // Click schedule grid cell (empty or filled)
//   $(document).on('click', '.js-schedule-cell', function () {
//     const date = $(this).data('date');
//     const time = $(this).data('time');

//     // If you want: only allow schedule by selecting mission first
//     // We'll show dropdown picker using SweetAlert
//

//     if (!missions.length) {
//       Swal.fire({
//         icon: 'warning',
//         title: 'No mission with PIC',
//         text: 'Assign PIC first before scheduling.'
//       });
//       return;
//     }

//     let optionsHtml = missions.map(m => `<option value="${m.id}">${m.code} - ${m.hospital} (PIC: ${m.pic})</option>`).join('');

//     Swal.fire({
//       title: 'Select Mission',
//       html: `<select id="sw_mission_id" class="swal2-input" style="width:100%">${optionsHtml}</select>`,
//       showCancelButton: true,
//       confirmButtonText: 'Next',
//     }).then((r) => {
//       if (!r.isConfirmed) return;

//       const selectedId = $('#sw_mission_id').val();
//       const mission = missions.find(x => String(x.id) === String(selectedId));
//       if (!mission) return;

//       openModalWith(mission, date, time);
//     });
//   });

  // Save schedule
  $('#btnSaveSchedule').on('click', function () {
    const missionId = $('#sm_mission_id').val();
    const date = $('#sm_date_input').val();
    const time = $('#sm_time_input').val();
    const duration = $('#sm_duration').val();

    if (!missionId) {
      Swal.fire({ icon:'warning', title:'Select Mission', text:'Please select a mission (PIC required).' });
      return;
    }
    if (!date || !time) {
      Swal.fire({ icon:'warning', title:'Date & Time required', text:'Please fill date and time.' });
      return;
    }

    // enforce 30-minute slot
    const mm = time.split(':')[1];
    if (!(mm === '00' || mm === '30')) {
      Swal.fire({ icon:'warning', title:'Invalid time', text:'Use 30-minute slot (00 / 30).' });
      return;
    }

    Swal.fire({
      icon: 'question',
      title: 'Confirm Schedule?',
      html: `<b>${date}</b> at <b>${time}</b><br>Duration: <b>${duration} min</b>`,
      showCancelButton: true,
      confirmButtonText: 'Yes, Save',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: window.missionPoolScheduleUrl,
        method: 'POST',
        data: {
          _token: window.csrfToken,
          mission_id: missionId,
          schedule_date: date,
          schedule_time: time,
          duration: duration,
        },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Saved', text: r.message || 'OK', timer: 1200, showConfirmButton:false });
          $('#scheduleModal').modal('hide');
          setTimeout(() => location.reload(), 800);
        },
        error: function (xhr) {
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
</script>


{{-- js for detail --}}
<script>
  $(document).on('click', '.js-mission-detail', function () {
  var id = $(this).data('id');

  $('#md_loading').show();
  $('#md_content').hide();

  $.get("{{ url('/missions') }}/" + id + "/detail", function(res){
    var m = res.mission || {};

    $('#md_code').text(m.code || '-');
    $('#md_hospital').text(m.hospital || '-');
    $('#md_pic').text(m.pic || '-');
    $('#md_deadline').text(m.deadline || '-');
    $('#md_purpose').text(m.task_purpose || '-');
    $('#md_urgency').text(m.priority_level || '-');
    $('#md_expected').text(m.expected_outcome || '-');

    var body = $('#md_history_body');
    body.empty();

    var hs = res.histories || [];
    if (!hs.length) {
      body.append('<tr><td colspan="4" class="text-center text-muted">No history</td></tr>');
    } else {
      hs.forEach(function(h){
        body.append(`
          <tr>
            <td>${h.time || '-'}</td>
            <td>${h.action || '-'}</td>
            <td>${h.actor || '-'}</td>
            <td>${h.note || ''}</td>
          </tr>
        `);
      });
    }

    $('#md_loading').hide();
    $('#md_content').show();
  }).fail(function(xhr){
    $('#md_loading').hide();
    Swal.fire({ icon:'error', title:'Failed', text:'Cannot load mission detail.' });
  });
});
</script>


{{-- start button --}}
<script>
  // For bulk schedule modal: update selected count
    $(document).on('click', '.js-start-mission', function () {
    var id = $(this).data('id');
    var code = $(this).data('code');
    var ref = ($(this).data('taskref') || '').toString();

    Swal.fire({
        title: 'Start Mission?',
        text: 'Mission: ' + (code || ''),
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Start',
        cancelButtonText: 'Cancel'
    }).then((r) => {
        if (!r.isConfirmed) return;

        $.ajax({
        url: "{{ url('/missions') }}/" + id + "/start",
        method: "POST",
        data: { _token: window.csrfToken },
        success: function(resp){
            // redirect to report form
            window.location.href = "{{ url('/missions') }}/" + id + "/report";
        },
        error: function(xhr){
            Swal.fire({ icon:'error', title:'Failed', text:'Cannot start mission.' });
        }
        });
    });
    });
</script>

<script>
$(function () {

  $(document).on('click', '.js-run-detail', function () {
    const runId = $(this).data('run-id');
    const runCode = $(this).data('run-code') || '-';

    $('#runTasksTitle').html('Loading tasks for <b>'+ runCode +'</b> ...');
    $('#runTasksWrap').html('<div class="text-center text-muted py-4">Loading...</div>');

    $.get("{{ url('/missions/mission-run') }}/" + runId + "/tasks", function (html) {
      $('#runTasksTitle').html('Mission: <b>'+ runCode +'</b>');
      $('#runTasksWrap').html(html);
    }).fail(function (xhr) {
      console.error(xhr.responseText);
      $('#runTasksTitle').html('Click <b>Detail</b> from the mission to show data...');
      $('#runTasksWrap').html('<div class="text-center text-danger py-4">Failed to load tasks. Check server log.</div>');
    });
  });

});
</script>

<script>
$(function () {

  // open schedule modal
  $(document).on('click', '.btn-schedule-mission', function () {
    const runId = $(this).data('run-id');
    const runCode = $(this).data('run-code') || ('RUN-' + runId);

    $('#sr_run_id').val(runId);
    $('#sr_run_code').text(runCode);

    // default date = today
    const today = new Date().toISOString().slice(0,10);
    $('#sr_date').val(today);

    $('#scheduleRunModal').modal('show');
  });

  // save schedule
  $('#btnSaveRunSchedule').on('click', function () {
    const runId = $('#sr_run_id').val();
    const date  = $('#sr_date').val();
    const time  = $('#sr_time').val();
    const dur   = $('#sr_duration').val();

    if (!runId || !date || !time || !dur) {
      Swal.fire({ icon:'warning', title:'Incomplete', text:'Please complete schedule fields.' });
      return;
    }

    Swal.fire({
      title: 'Confirm Schedule?',
      html: `Date: <b>${date}</b><br>Time: <b>${time}</b><br>Duration: <b>${dur}</b> minutes`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Save',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: "{{ route('missionrun.schedule') }}",
        method: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          run_id: runId,
          schedule_date: date,
          schedule_time: time,
          duration_minutes: dur,
        },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Scheduled', text: r.message || 'OK', timer: 1200, showConfirmButton:false });
          $('#scheduleRunModal').modal('hide');
          setTimeout(() => location.reload(), 800);
        },
        error: function (xhr) {
          let msg = 'Failed. Check server log.';
          try {
            const j = JSON.parse(xhr.responseText);
            if (j.message) msg = j.message;
          } catch(e) {}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

});
</script>

<script>
$(function () {

  $(document).on('click', '.btn-start-run', function () {
    const runId = $(this).data('run-id');
    const runCode = $(this).data('run-code') || ('RUN-' + runId);

    Swal.fire({
      title: 'Start Mission?',
      html: `Mission: <b>${runCode}</b><br>This will set status to <b>On Going</b>.`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Yes, Start',
      cancelButtonText: 'Cancel'
    }).then((res) => {
      if (!res.isConfirmed) return;

      $.ajax({
        url: "{{ url('/missions/runs') }}/" + runId + "/start",
        method: "POST",
        data: { _token: "{{ csrf_token() }}" },
        success: function (r) {
          Swal.fire({ icon:'success', title:'Started', timer: 900, showConfirmButton:false });
          if (r.redirect) {
            setTimeout(() => window.location.href = r.redirect, 650);
          } else {
            setTimeout(() => location.reload(), 650);
          }
        },
        error: function (xhr) {
          let msg = 'Failed. Check server log.';
          try {
            const j = JSON.parse(xhr.responseText);
            if (j.message) msg = j.message;
          } catch(e){}
          Swal.fire({ icon:'error', title:'Error', text: msg });
        }
      });
    });
  });

});
</script>
