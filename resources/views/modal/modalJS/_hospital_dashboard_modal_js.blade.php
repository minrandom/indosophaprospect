<script>
(function () {
  if (!$('#ibSummaryModal').length) return;

  var inited = false;

  $('#ibSummaryModal').on('shown.bs.modal', function () {
    if (inited) return;

    if (typeof Chart === 'undefined') {
      console.error('Chart.js not loaded');
      return;
    }

    // Warranties
    var wEl = document.getElementById('modalWarrantiesChart');
    if (wEl) {
      new Chart(wEl, {
        type: 'horizontalBar',
        data: {
          labels: ['Active', 'Ending Soon', 'Finished/ No Cover'],
          datasets: [{
            label: 'Count',
            data: @json($top['warranties'] ?? [10, 5, 2]),
            backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { display: false },
          scales: { xAxes: [{ ticks: { beginAtZero: true } }] }
        }
      });
    }

    // Under Contract
    var cEl = document.getElementById('modalContractChart');
    if (cEl) {
      new Chart(cEl, {
        type: 'doughnut',
        data: {
          labels: ['Under Contract', 'Not Under Contract'],
          datasets: [{
            data: @json($top['under_contract'] ?? [60, 40]),
            backgroundColor: ['#36b9cc', 'red']
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { position: 'bottom' }
        }
      });
    }

    // Departments
    var dEl = document.getElementById('modalDepartmentsChart');
    if (dEl) {
      new Chart(dEl, {
        type: 'bar',
        data: {
          labels: @json($top['departments']['labels'] ?? ['ICU','URO','CV']),
          datasets: [{
            label: 'Count',
            data: @json($top['departments']['data'] ?? [3,7,5]),
            backgroundColor: '#4e73df'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          legend: { display: false },
          scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
        }
      });
    }

    inited = true;
  });

  // Optional: if you want charts to re-render every time modal opens (fresh data)
  // $('#ibSummaryModal').on('hidden.bs.modal', function(){ inited = false; });

})();
</script>
