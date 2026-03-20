let alertTimer = null; // global timer

// Auto-create alert HTML when page loads
document.addEventListener("DOMContentLoaded", function () {
  const overlay = document.createElement("div");
  overlay.className = "alert-overlay";
  overlay.id = "customAlert";

  overlay.innerHTML = `
    <div class="alert-box">
      <div class="alert-icon" id="alertIcon"></div>
      <h2 id="alertTitle"></h2>
      <p id="alertMessage"></p>
      <button class="alert-btn" id="alertBtn">OK</button>
    </div>
  `;

  document.body.appendChild(overlay);
});

function showAlert(type, title, message, buttonText = "OK", callback = null) {
  const overlay = document.getElementById("customAlert");
  const icon = document.getElementById("alertIcon");
  const alertTitle = document.getElementById("alertTitle");
  const alertMessage = document.getElementById("alertMessage");
  const alertBtn = document.getElementById("alertBtn");

  alertTitle.textContent = title;
  alertMessage.textContent = message;
  alertBtn.textContent = buttonText;

  // Reset
  icon.innerHTML = "";
  alertBtn.style.background = "";

  // Clear previous timer if any
  if (alertTimer) {
    clearTimeout(alertTimer);
  }

 switch (type) {
  case "success":
    icon.innerHTML =
      '<img src="/POS-GAS/frontend/assets/success.png" style="width:150px;">';
    alertBtn.style.background = "#28a745";
    alertTitle.style.color = "#28a745";
    break;

  case "error":
    icon.innerHTML =
      '<img src="/POS-GAS/frontend/assets/error.png" style="width:150px;">';
    alertBtn.style.background = "#dc3545";
    alertTitle.style.color = "#dc3545";
    break;

  case "warning":
    icon.innerHTML =
      '<img src="/POS-GAS/frontend/assets/warning.png" style="width:150px;">';
    alertBtn.style.background = "#ffc107";
    alertTitle.style.color = "#ffc107";
    break;

  case "info":
    icon.innerHTML =
      '<img src="/POS-GAS/frontend/assets/info.png" style="width:150px;">';
    alertBtn.style.background = "#17a2b8";
    alertTitle.style.color = "#17a2b8";
    break;
}

  overlay.style.display = "flex";

  const alertBox = overlay.querySelector(".alert-box");
  alertBox.classList.remove("shake"); // reset

  // Apply animation based on type
  if (type === "error" || type === "warning") {
    setTimeout(() => {
      alertBox.classList.add("shake");
    }, 50);
  } else if (type === "success") {
    // Fireworks effect
    const colors = ["#28a745", "#5fd1c2", "#ffc107", "#17a2b8"];
    const particleCount = 12; // number of sparks

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement("div");
      particle.className = "firework-particle";

      // Random direction for each particle
      const angle = (i / particleCount) * 2 * Math.PI;
      const distance = 30 + Math.random() * 100; // distance of explosion
      particle.style.setProperty("--x", `${Math.cos(angle) * distance}px`);
      particle.style.setProperty("--y", `${Math.sin(angle) * distance}px`);

      // Random color
      particle.style.background =
        colors[Math.floor(Math.random() * colors.length)];

      alertBox.appendChild(particle);

      // Remove particle after animation
      setTimeout(() => particle.remove(), 600);
    }
  }
  // Info type: just popIn (default CSS), nothing extra

  // Button click handler
  alertBtn.onclick = function () {
    // Stop auto timer if user clicked
    if (alertTimer) {
      clearTimeout(alertTimer);
    }

    overlay.style.display = "none";
    if (callback) callback();
  };

  // ✅ AUTO CLICK AFTER 2 SECONDS
  alertTimer = setTimeout(() => {
    alertBtn.click(); // simulate user click
  }, 2000);
}
