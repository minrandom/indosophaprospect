<script>
(function () {
  if (typeof Chart === 'undefined') {
    console.error('Chart.js not loaded');
    return;
  }

  // Prevent re-rendering if user clicks tab multiple times
  var ibChartsRendered = false;

  function renderIbCharts() {
    if (ibChartsRendered) return;

    var coveredEl  = document.getElementById('deptCoveredChart');
    var warrantyEl = document.getElementById('deptWarrantyChart');
    var contractEl = document.getElementById('deptContractChart');

    if (!coveredEl || !warrantyEl || !contractEl) return;

    // Covered donut
    new Chart(coveredEl, {
      type: 'doughnut',
      data: {
        labels: ['Covered', 'Not Covered'],
        datasets: [{
          data: @json($ib['covered_pie'] ?? [0,0]),
          backgroundColor: ['#1cc88a', '#8e9ac7']
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { position: 'bottom',labels :{fontColor:'#fff'} }
      }
    });

    // Warranty horizontal bar
    new Chart(warrantyEl, {
      type: 'horizontalBar', // SB Admin 2 uses Chart.js v2
      data: {
        labels: ['Active','Ending Soon','Finished'],
        datasets: [{
          data: @json($ib['warranties'] ?? [0,0,0]),
          backgroundColor: ['#1cc88a','#f6c23e','#e74a3b']
        }]
      },
      options: {
        responsive:true,
        maintainAspectRatio:false,
        legend:{display:false},
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: '#ffff' // 🔥 X axis text
                }
            }],
            yAxes: [{
                ticks: {
                    fontColor: '#ffff' // 🔥 Y axis text
                }
            }]
        }
      }
    });

    // Contract horizontal bar
    new Chart(contractEl, {
      type: 'horizontalBar',
      data: {
        labels: ['Active','Ending Soon','Finished'],
        datasets: [{
          data: @json($ib['contracts'] ?? [0,0,0]),
          backgroundColor: ['#4e73df','#f6c23e','#e74a3b']
        }]
      },
      options: {
        responsive:true,
        maintainAspectRatio:false,
        legend:{display:false},
        scales: {
            xAxes: [{
                ticks: {
                    beginAtZero: true,
                    fontColor: '#ffff' // 🔥 X axis text
                }
            }],
            yAxes: [{
                ticks: {
                    fontColor: '#ffff' // 🔥 Y axis text
                }
            }]
        }
      }
    });

    ibChartsRendered = true;
  }

  // If IB tab is default active on load
  document.addEventListener('DOMContentLoaded', function () {
    var ibPane = document.getElementById('ib');
    if (ibPane && ibPane.classList.contains('active') && ibPane.classList.contains('show')) {
      renderIbCharts();
    }
  });

  // Bootstrap tab event (BS4 uses shown.bs.tab)
  $(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
    var target = $(e.target).attr('href'); // e.g. #ib
    if (target === '#ib') {
      // small delay helps if layout still animating
      setTimeout(renderIbCharts, 150);
    }
  });

})();
</script>
