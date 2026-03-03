<script>
    $(function () {
  const $prov = $('#province_id');
  const $hosp = $('#hospital_id');
  const hospitalsUrlTpl = window.taskPoolHospitalsUrl; // set from blade
  const selectedHospitalId = window.selectedHospitalId || '';

  if (!$prov.length || !$hosp.length) return;

  // select2 optional
  if ($.fn.select2) {
    $prov.select2({ width: '100%', placeholder: 'All Provinces' });
    $hosp.select2({ width: '100%', placeholder: 'All Hospitals' });
  }

  function resetHospital(disabled) {
    $hosp.empty()
      .append(new Option('All Hospitals', '', true, true))
      .prop('disabled', !!disabled)
      .trigger('change');
  }

  function loadHospitals(provinceId, keepSelectedId) {
    resetHospital(true);
    if (!provinceId) return;

    const url = hospitalsUrlTpl.replace('__ID__', provinceId);

    $.get(url, function (rows) {
      resetHospital(false);

      rows.forEach(function (h) {
        const opt = new Option(h.name, h.id, false, false);
        $hosp.append(opt);
      });

      if (keepSelectedId) {
        $hosp.val(String(keepSelectedId)).trigger('change');
      }
    });
  }

  // on change province
  $prov.on('change', function () {
    const provinceId = $(this).val();
    loadHospitals(provinceId, '');
  });

  // initial load if province already selected (page refresh)
  if ($prov.val()) {
    loadHospitals($prov.val(), selectedHospitalId);
  } else {
    resetHospital(true);
  }
});
</script>
    <script>
    $(function () {
    var checklistMode = false;

    function getSelectedIds() {
        var ids = [];
        $('.chk-mission:checked').each(function () {
        ids.push($(this).val());
        });
        return ids;
    }

    function toggleChecklist(show) {
        checklistMode = show;

        if (show) {
        $('.col-check').removeClass('d-none');
        $('#btnSelectAll, #btnUnselectAll').removeClass('d-none');
        $('#btnToggleChecklist').text('Create Mission (Selected)');
        } else {
        $('.col-check').addClass('d-none');
        $('.chk-mission').prop('checked', false);
        $('#btnSelectAll, #btnUnselectAll').addClass('d-none');
        $('#btnToggleChecklist').text('Create Mission');
        }
    }

    // Global button
    $('#btnToggleChecklist').on('click', function () {
        if (!checklistMode) {
        toggleChecklist(true);
        return;
        }

        // already in checklist mode: open modal if selected
        var ids = getSelectedIds();
        if (!ids.length) {
        Swal.fire({ icon:'warning', title:'No selected data', text:'Please check at least 1 task.' });
        return;
        }

        $('#selectedCount').text(ids.length);
        $('#bulkMissionModal').modal('show');
    });

    $('#btnSelectAll').on('click', function () {
        $('.chk-mission').prop('checked', true);
    });

    $('#btnUnselectAll').on('click', function () {
        $('.chk-mission').prop('checked', false);
    });

    // Confirm bulk create mission
    $('#btnConfirmBulk').on('click', function () {
        var ids = getSelectedIds();
        var pic = $('#pic_user_id').val();
        var deadline = $('#bulk_deadline').val();

        if (!ids.length) {
        Swal.fire({ icon:'warning', title:'No selected data', text:'Please check at least 1 task.' });
        return;
        }
        if (!deadline) {
        Swal.fire({ icon:'warning', title:'Deadline required', text:'Please select deadline.' });
        return;
        }

        Swal.fire({
        title: 'Confirm Create Mission?',
        html: `Selected: <b>${ids.length}</b> task(s)<br>Deadline: <b>${deadline}</b>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes, Create',
        cancelButtonText: 'Cancel'
        }).then((res) => {
        if (!res.isConfirmed) return;

        $.ajax({
            url: "{{ route('missions.bulkToMission') }}",
            method: "POST",
            data: {
            _token: "{{ csrf_token() }}",
            mission_ids: ids,
            pic_user_id: pic,
            deadline: deadline,
            },
            success: function (r) {
            Swal.fire({ icon:'success', title:'Success', text: r.message || 'Done', timer: 1400, showConfirmButton:false });
            $('#bulkMissionModal').modal('hide');
            setTimeout(() => location.reload(), 900);
            },
            error: function (xhr) {
            console.error(xhr.responseText);
            Swal.fire({ icon:'error', title:'Failed', text:'Check console/server log.' });
            }
        });
        });
    });

    });


    $('#btnClearFilter').on('click', function () {
        // Reset selects
        $('#province_id').val('').trigger('change');
        $('#hospital_id').empty()
            .append(new Option('All Hospitals', '', true, true))
            .prop('disabled', true)
            .trigger('change');

        // Reload page without query params
        window.location.href = "{{ route('missions.taskPool') }}";
        });



    </script>
