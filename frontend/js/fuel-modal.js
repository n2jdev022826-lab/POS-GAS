// ================= MODALS =================
function addFuel() {
  document.getElementById("addFuelModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

function closeFuelModal() {
  document.getElementById("addFuelModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditFuelModal() {
  document.getElementById("editFuelModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function openRefillModal() {
  document.getElementById("refillModal").style.display = "flex";
}

function closeRefillModal() {
  document.getElementById("refillModal").style.display = "none";
}

// ================= ADD FUEL =================
document.getElementById("addFuelForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/POS-GAS/api/fuel/create.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.json())
    .then(json => {
      if (json.status === "success") {
        showAlert("success", "Success", json.message);
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert("error", "Failed", json.message);
      }
    })
    .catch(() => {
      showAlert("error", "Error", "Error saving fuel");
    });
});

// ================= EDIT FUEL =================
function editFuel(fuelId) {
  const fuel = fuels.find(f => f.id === fuelId);
  if (!fuel) return;

  document.getElementById("edit_fuel_id").value = fuel.id;
  document.getElementById("edit_name").value = fuel.name || "";
  document.getElementById("edit_price").value = fuel.price_per_liter || "";

  document.getElementById("editFuelModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

// ================= UPDATE =================
document.getElementById("editFuelForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/POS-GAS/api/fuel/update.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === "success") {
        showAlert("success", "Updated", data.message);
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert("error", "Failed", data.message);
      }
    })
    .catch(() => {
      showAlert("error", "Error", "Update failed");
    });
});

// ================= DELETE =================
function deleteFuel(fuelId) {
  showAlert(
    "confirm",
    "Delete Fuel",
    "Are you sure you want to delete this fuel?",
    "Delete",
    function (confirmed) {
      if (!confirmed) return;

      fetch("/POS-GAS/api/fuel/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ fuel_id: fuelId }),
      })
        .then(res => res.json())
        .then(data => {
          if (data.status === "success") {
            showAlert("success", "Deleted", data.message);
            setTimeout(() => location.reload(), 1000);
          } else {
            showAlert("error", "Failed", data.message);
          }
        })
        .catch(() => {
          showAlert("error", "Error", "Delete failed");
        });
    }
  );
}

// ================= REFILL =================
document.getElementById("refillForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/POS-GAS/api/fuel/refill.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.json())
    .then(json => {
      if (json.status === "success") {
        showAlert("success", "Success", json.message);
        setTimeout(() => {
          closeRefillModal();
          location.reload();
        }, 1000);
      } else {
        showAlert("error", "Failed", json.message);
      }
    })
    .catch(() => {
      showAlert("error", "Error", "Refill failed");
    });
});