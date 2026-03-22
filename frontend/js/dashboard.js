// WAIT UNTIL PAGE FULLY LOADS
window.addEventListener("load", function () {

  // ✅ SAFE FALLBACKS FOR LINE CHART
  const weeklySalesData = typeof weeklySales !== "undefined"
    ? weeklySales
    : [0,0,0,0,0,0,0];

  /* ================= LINE CHART ================= */
  const lineCtx = document.getElementById('lineChart');
  if (lineCtx) { // ✅ ensure element exists
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

    /* ================= RESIZE FIX ================= */
    window.addEventListener('resize', function () {
      lineChart.resize();
    });
  }

  /* ================= FUEL TANK LEVELS ================= */
  document.querySelectorAll(".fuel").forEach(fuel => {
    const tankCard = fuel.closest(".tank-card");
    const liters = parseFloat(fuel.dataset.liters) || 0;
    const maxCapacity = parseFloat(fuel.dataset.maxLiters) || 10000;
    const percent = Math.min((liters / maxCapacity) * 100, 100);

    fuel.style.transition = "height 1.5s ease-in-out";
    fuel.style.height = percent + "%";

    const percentText = tankCard.querySelector(".percent");
    if (percentText) percentText.innerText = percent.toFixed(1) + "%";

    if (percent < 20) {
      fuel.classList.add("low");
      tankCard.classList.add("alert");
    } else {
      fuel.classList.remove("low");
      tankCard.classList.remove("alert");
    }

    const wave = fuel.querySelector(".wave");
    if (wave) wave.style.animationDuration = `${Math.max(0.5, percent / 50 + 1)}s`;
  });

});