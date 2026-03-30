<script>
$(function () {

  function formatDateLabel(dateObj) {
    const options = { day: '2-digit', month: 'short', year: 'numeric' };
    return dateObj.toLocaleDateString('en-GB', options).replace(/ /g, ' ');
  }

  function buildWeekTable($modal, startDateStr) {
    const start = new Date(startDateStr + 'T00:00:00');
    const $body = $modal.find('.calendar-grid-body');
    const $thead = $modal.find('thead tr');

    const hours = ['08:00','10:00','12:00','14:00','16:00','18:00','20:00','22:00'];
    const dayNames = ['SUN','MON','TUE','WED','THU','FRI','SAT'];

    // update title
    $modal.find('.calendar-week-label').text(formatDateLabel(start));

    // rebuild header
    let headHtml = '<th style="min-width:90px;">Time</th>';
    for (let i = 0; i < 7; i++) {
      const d = new Date(start);
      d.setDate(start.getDate() + i);

      const yyyy = d.getFullYear();
      const mm = String(d.getMonth() + 1).padStart(2, '0');
      const dd = String(d.getDate()).padStart(2, '0');
      const iso = `${yyyy}-${mm}-${dd}`;

      const label = `${dd} ${d.toLocaleString('en-US', { month: 'short' })}`;

      headHtml += `
        <th style="min-width:140px;">
          <div class="font-weight-bold">${dayNames[d.getDay()]}</div>
          <div class="small text-muted">${label}</div>
        </th>
      `;
    }
    $thead.html(headHtml);

    // rebuild body
    let bodyHtml = '';
    for (let h = 0; h < hours.length; h++) {
      bodyHtml += `<tr><th class="align-middle">${hours[h]}</th>`;

      for (let i = 0; i < 7; i++) {
        const d = new Date(start);
        d.setDate(start.getDate() + i);

        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        const iso = `${yyyy}-${mm}-${dd}`;

        bodyHtml += `
          <td class="align-middle calendar-slot"
              data-date="${iso}"
              data-time="${hours[h]}"></td>
        `;
      }

      bodyHtml += `</tr>`;
    }

    $body.html(bodyHtml);
    $body.attr('data-start', startDateStr);
  }

  $(document).on('click', '.btn-calendar-prev', function () {
    const target = $(this).data('target');
    const $modal = $(target);
    const currentStart = $modal.find('.calendar-grid-body').attr('data-start');
    const d = new Date(currentStart + 'T00:00:00');
    d.setDate(d.getDate() - 7);

    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');

    buildWeekTable($modal, `${yyyy}-${mm}-${dd}`);
  });

  $(document).on('click', '.btn-calendar-next', function () {
    const target = $(this).data('target');
    const $modal = $(target);
    const currentStart = $modal.find('.calendar-grid-body').attr('data-start');
    const d = new Date(currentStart + 'T00:00:00');
    d.setDate(d.getDate() + 7);

    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');

    buildWeekTable($modal, `${yyyy}-${mm}-${dd}`);
  });

});
</script>
