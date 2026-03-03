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
  $(document).on('click', '.js-open-schedule', function () {
    const mission = {
      id: $(this).data('mission-id'),
      code: $(this).data('mission-code'),
      hospital: $(this).data('hospital'),
      pic: $(this).data('pic'),
    };

    // default = today cell (optional), here use weekStart Monday + 08:00
    const firstDate = "{{ $weekStart->toDateString() }}";
    openModalWith(mission, firstDate, '08:00');
  });

  // Click schedule grid cell (empty or filled)
  $(document).on('click', '.js-schedule-cell', function () {
    const date = $(this).data('date');
    const time = $(this).data('time');

    // If you want: only allow schedule by selecting mission first
    // We'll show dropdown picker using SweetAlert
    const missions = @json($missionsToScheduleJs);

    if (!missions.length) {
      Swal.fire({
        icon: 'warning',
        title: 'No mission with PIC',
        text: 'Assign PIC first before scheduling.'
      });
      return;
    }

    let optionsHtml = missions.map(m => `<option value="${m.id}">${m.code} - ${m.hospital} (PIC: ${m.pic})</option>`).join('');

    Swal.fire({
      title: 'Select Mission',
      html: `<select id="sw_mission_id" class="swal2-input" style="width:100%">${optionsHtml}</select>`,
      showCancelButton: true,
      confirmButtonText: 'Next',
    }).then((r) => {
      if (!r.isConfirmed) return;

      const selectedId = $('#sw_mission_id').val();
      const mission = missions.find(x => String(x.id) === String(selectedId));
      if (!mission) return;

      openModalWith(mission, date, time);
    });
  });

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
