// OPEN MODAL
function addFuel() {
  const modal = document.getElementById("addFuelModal");

  modal.style.display = "flex";
  document.body.classList.add("modal-open");
}

// CLOSE MODAL
function closeFuelModal() {
  const modal = document.getElementById("addFuelModal");

  modal.style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditFuelModal() {
  document.getElementById("editFuelModal").style.display = "none";
  document.body.classList.remove("modal-open");
}


// ================= ADD FUEL =================
document.getElementById("addFuelForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/fuels/create.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log("RAW RESPONSE:", data);

      let json;

      try {
        json = JSON.parse(data);
      } catch (e) {
        console.error("JSON ERROR:", e);
        showAlert("error", "Server Error", "Invalid response");
        return;
      }

      console.log("Server Response:", json);

      if (json.status === "success") {
        showAlert("success", "Success", json.message);

        setTimeout(() => {
          location.reload();
        }, 1000);

      } else {
        showAlert("error", "Failed", json.message);
      }
    })
    .catch((err) => {
      console.error("Fetch Error:", err);
      showAlert("error", "Error", "Error saving fuel");
    });
});


// ================= EDIT FUEL =================
function editFuel(fuelCode) {

  const fuel = fuels.find(f => f.fuel_code === fuelCode);

  if (!fuel) return;

  // Fill fields
  document.getElementById("edit_fuel_code").value = fuel.fuel_code;
  document.getElementById("edit_name").value = fuel.name || "";
  document.getElementById("edit_price").value = fuel.price_per_liter || "";

  // Open modal
  document.getElementById("editFuelModal").style.display = "flex";
  document.body.classList.add("modal-open");
}


// ================= UPDATE FUEL =================
document.getElementById("editFuelForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/fuels/update.php", {
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
    .catch(err => {
      console.error(err);
      showAlert("error", "Error", "Update failed");
    });
});


// ================= DELETE FUEL =================
function deleteFuel(fuelCode) {

  showAlert(
    "confirm",
    "Delete Fuel",
    "Are you sure you want to delete this fuel?",
    "Delete",
    function (confirmed) {

      if (!confirmed) return;

      fetch("http://localhost/POS-GAS/api/fuels/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          fuel_code: fuelCode,
        }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            showAlert("success", "Deleted", data.message);
            setTimeout(() => location.reload(), 1000);
          } else {
            showAlert("error", "Failed", data.message);
          }
        })
        .catch((err) => {
          console.error(err);
          showAlert("error", "Error", "Delete failed");
        });
    }
  );
}