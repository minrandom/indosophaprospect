$(function () {
  // Only run when the search form exists
  if (!$('#hospitalSearchForm').length) return;

  // Init Province Select2 (province options already rendered by Blade)
  $('#province_id').select2({
    placeholder: 'Select Province',
    width: '100%'
  });

  // Disable hospital initially
  $('#hospital_id').prop('disabled', true);

  // When province changes
  $('#province_id').on('change', function () {
    const provinceId = $(this).val();

    // Reset hospital dropdown
    populateSelectFromDatalist(
      'hospital_id',
      [],
      'Select Hospital'
    );
    $('#hospital_id').prop('disabled', true);

    if (!provinceId) return;

    // Call your EXISTING route
    const url = window.hospitalByProvinceUrl.replace('__ID__', provinceId);

    $.get(url, function (res) {
      // Use your existing response key
      const hospitals = res.hosopt || [];

      populateSelectFromDatalist(
        'hospital_id',
        hospitals,
        'Select Hospital'
      );

      $('#hospital_id').prop('disabled', false);
    }).fail(function (xhr) {
      console.error('Failed loading hospitals', xhr.status);
    });
  });

  // Submit → redirect to hospital dashboard
  $('#hospitalSearchForm').on('submit', function (e) {
    e.preventDefault();

    const hospitalId = $('#hospital_id').val();
    if (!hospitalId) return;

    window.location.href = `${window.hospitalDashboardBaseUrl}/${hospitalId}`;
  });
});
