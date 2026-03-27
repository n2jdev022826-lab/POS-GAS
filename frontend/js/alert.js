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
      
      <div class="alert-actions">
        <button class="alert-btn cancel-btn" id="alertCancelBtn">Cancel</button>
        <button class="alert-btn" id="alertBtn">OK</button>
      </div>
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
  const cancelBtn = document.getElementById("alertCancelBtn");

  // Reset UI
  cancelBtn.style.display = "none";
  icon.innerHTML = "";
  alertBtn.style.background = "";
  alertBtn.textContent = buttonText;

  alertTitle.textContent = title;
  alertMessage.textContent = message;

  // Clear previous timer
  if (alertTimer) {
    clearTimeout(alertTimer);
  }

  // TYPE STYLES
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

    case "confirm":
      icon.innerHTML =
        '<img src="/POS-GAS/frontend/assets/warning.png" style="width:150px;">';
      alertBtn.style.background = "#dc3545";
      alertTitle.style.color = "#dc3545";

      cancelBtn.style.display = "inline-block";
      break;
  }

  overlay.style.display = "flex";

  const alertBox = overlay.querySelector(".alert-box");
  alertBox.classList.remove("shake");

  // Animation
  if (type === "error" || type === "warning" || type === "confirm") {
    setTimeout(() => {
      alertBox.classList.add("shake");
    }, 50);
  } else if (type === "success") {
    const colors = ["#28a745", "#5fd1c2", "#ffc107", "#17a2b8"];
    const particleCount = 12;

    for (let i = 0; i < particleCount; i++) {
      const particle = document.createElement("div");
      particle.className = "firework-particle";

      const angle = (i / particleCount) * 2 * Math.PI;
      const distance = 30 + Math.random() * 100;

      particle.style.setProperty("--x", `${Math.cos(angle) * distance}px`);
      particle.style.setProperty("--y", `${Math.sin(angle) * distance}px`);
      particle.style.background =
        colors[Math.floor(Math.random() * colors.length)];

      alertBox.appendChild(particle);
      setTimeout(() => particle.remove(), 600);
    }
  }

  // OK BUTTON
  alertBtn.onclick = function () {
    if (alertTimer) clearTimeout(alertTimer);

    overlay.style.display = "none";

    if (callback) callback(true); // TRUE = confirmed
  };

  // CANCEL BUTTON (ONLY FOR CONFIRM)
  cancelBtn.onclick = function () {
    if (alertTimer) clearTimeout(alertTimer);

    overlay.style.display = "none";

    if (callback) callback(false); // FALSE = cancelled
  };

  // ✅ AUTO CLOSE ONLY IF NOT CONFIRM
  if (type !== "confirm") {
    alertTimer = setTimeout(() => {
      alertBtn.click();
    }, 2000);
  }
}

