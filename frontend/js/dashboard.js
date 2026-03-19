// WAIT UNTIL PAGE FULLY LOADS
window.addEventListener("load", function () {

  // ✅ SAFE FALLBACKS
  const weeklySalesData = typeof weeklySales !== "undefined"
    ? weeklySales
    : [0,0,0,0,0,0,0];

  const pieLabelsData = typeof pieLabels !== "undefined"
    ? pieLabels
    : ["No Data"];

  const pieValuesData = typeof pieData !== "undefined"
    ? pieData
    : [1];

  /* ================= LINE CHART ================= */
  const lineCtx = document.getElementById('lineChart');

  const lineChart = new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'],
      datasets: [{
        label: 'Sales',
        data: weeklySalesData,
        borderColor: '#00a8c6',
        backgroundColor: 'rgba(0,168,198,0.1)',
        tension: 0.4,
        fill: true,
        pointBackgroundColor:'#94c1ff'
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      resizeDelay: 200,
      plugins: {
        legend: { display: false }
      }
    }
  });

 /* ================= PIE CHART ================= */
const pieCtx = document.getElementById('pieChart');

const pieChart = new Chart(pieCtx, {
  type: 'pie',
  data: {
    labels: pieLabelsData,
    datasets: [{
      data: pieValuesData,
      backgroundColor: [
        '#17a2b8',
        '#8e7cc3',
        '#f6a545',
        '#28a745',
        '#dc3545',
        '#ffc107'
      ]
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    resizeDelay: 200,
    plugins: {
      tooltip: {
        callbacks: {
          label: function(context) {
            let value = context.raw || 0;
            return context.label + ': ₱ ' + value.toLocaleString();
          }
        }
      }
    }
  }
});

  /* ================= RESIZE FIX ================= */
  window.addEventListener('resize', function () {
    lineChart.resize();
    pieChart.resize();
  });

});