<script>
$(function () {

  // ✅ shared state for "Show" and "Add Task"
  var currentRow = {};
  var currentMissing = [];

  $(document).on('click', '.js-show-missing', function () {

    // ✅ store into shared variables
    currentMissing = $(this).data('missing') || [];
    currentRow = $(this).data('row') || {};

    console.log('row data:', currentRow);

    // Missing badges
    var wrap = $('#md_missing_badges');
    wrap.empty();

    if (!currentMissing.length) {
      wrap.append('<span class="badge badge-success">No Missing Data</span>');
    } else {
      currentMissing.forEach(function (col) {
        wrap.append('<span class="badge badge-warning mr-1 mb-1">'+ col +'</span>');
      });
    }

    // Fill detail fields
    $('#md_installbase_id').val(currentRow.id || '');
    $('#md_hospital').text(currentRow.hospital || 'Missing Data');
    $('#md_city').text(currentRow.city || 'Missing Data');
    $('#md_province').text(currentRow.province || 'Missing Data');
    $('#md_installbase_code').text(currentRow.installbase_code || 'Missing Data');
    $('#md_brand').text(currentRow.brand || 'Missing Data');
    $('#md_model_type').text(currentRow.model_type || 'Missing Data');
    $('#md_category').text(currentRow.category || 'Missing Data');
    $('#md_pic_to_recall').text(currentRow.pic_to_recall || 'Missing Data');
    $('#md_department_phone').text(currentRow.department_phone || 'Missing Data');
    $('#md_serial_number').text(currentRow.serial_number || 'Missing Data');
    $('#md_department').text(currentRow.department || 'Missing Data');
    $('#md_installation_date').text(currentRow.installation_date || 'Missing Data');
    $('#md_installation_status').text(currentRow.installation_status || 'Missing Data');
    $('#md_end_of_warranty').text(currentRow.end_of_warranty || 'Missing Data');

  });

  // ✅ delegated click is safer for modal button
  $(document).on('click', '#btnAutoCreateMission', function () {

    if (!currentRow.id) {
      Swal.fire({
        icon: 'error',
        title: 'No Installbase Selected',
        text: 'Please click "Show" on a row first.'
      });
      return;
    }

    Swal.fire({
      title: 'Create Task?',
      html: `
        <strong>Installbase:</strong> ${currentRow.installbase_code || '-'} <br>
        <strong>Hospital:</strong> ${currentRow.hospital || '-'} <br><br>
        Missing fields will be added to expected outcome.
      `,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#1cc88a',
      cancelButtonColor: '#e74a3b',
      confirmButtonText: 'Yes, Create Task',
      cancelButtonText: 'Cancel'
    }).then((result) => {

      if (!result.isConfirmed) return;

      $.ajax({
        url: "{{ route('missions.autoFromInstallbase') }}",
        type: "POST",
        data: {
          _token: "{{ csrf_token() }}",
          installbase_id: currentRow.id,
          hospital_id: currentRow.hospital_id,
          department: currentRow.department,
          missing_columns: currentMissing
        },
        success: function (res) {

          Swal.fire({
            icon: 'success',
            title: 'Task Created!',
            text: res.message || 'Task successfully added.',
            timer: 2000,
            showConfirmButton: false
          });

          $('#missingDetailModal').modal('hide');
          // optional reload:
          // setTimeout(() => location.reload(), 800);
        },
        error: function (xhr) {
          console.error(xhr.responseText);

          Swal.fire({
            icon: 'error',
            title: 'Failed',
            text: 'Something went wrong. Check console.'
          });
        }
      });

    });

  });

});
</script>
