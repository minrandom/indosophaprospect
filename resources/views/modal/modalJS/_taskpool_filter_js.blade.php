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

