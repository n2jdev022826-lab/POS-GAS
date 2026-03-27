// ================= OPEN / CLOSE MODAL =================
function addPump() {
  document.getElementById("addPumpModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

function closePumpModal() {
  document.getElementById("addPumpModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditPumpModal() {
  document.getElementById("editPumpModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

// ================= ADD PUMP =================
document.getElementById("addPumpForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/pump/create.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.text()) // ✅ SAME AS SUPPLIER
    .then(data => {
      let json;

      try {
        json = JSON.parse(data);
      } catch (e) {
        console.error("JSON ERROR:", e);
        console.log("RAW RESPONSE:", data);
        showAlert("error", "Server Error", "Invalid response");
        return;
      }

      if (json.status === "success") {
        showAlert("success", "Success", json.message);
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert("error", "Failed", json.message);
      }
    })
    .catch(err => {
      console.error(err);
      showAlert("error", "Error", "Error saving pump");
    });
});

// ================= EDIT PUMP (FILL MODAL) =================
function editPump(code) {
  const p = pumps.find(x => x.pump_code === code);
  if (!p) return;

  document.getElementById("edit_pump_code").value = p.pump_code;
  document.getElementById("edit_pump_number").value = p.pump_name || "";
  document.getElementById("edit_fuel_id").value = p.fuel_id || "";
  document.getElementById("edit_status").value = p.status || "";

  document.getElementById("editPumpModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

// ================= UPDATE PUMP =================
document.getElementById("editPumpForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/pump/update.php", {
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

// ================= DELETE PUMP =================
function deletePump(code) {

  showAlert(
    "confirm",
    "Delete Pump",
    "Are you sure you want to delete this pump?",
    "Delete",
    function (confirmed) {

      if (!confirmed) return;

      fetch("http://localhost/POS-GAS/api/pump/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          pump_code: code,
        }),
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
        .catch(err => {
          console.error(err);
          showAlert("error", "Error", "Delete failed");
        });

    }
  );
}