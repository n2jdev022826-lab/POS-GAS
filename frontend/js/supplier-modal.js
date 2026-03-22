// ================= OPEN / CLOSE MODAL =================
function addSupplier() {
  document.getElementById("addSupplierModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

function closeSupplierModal() {
  document.getElementById("addSupplierModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditSupplierModal() {
  document.getElementById("editSupplierModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

// ================= ADD SUPPLIER =================
document.getElementById("addSupplierForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/suppliers/create.php", {
    method: "POST",
    body: formData,
  })
    .then(res => res.text())
    .then(data => {
      let json;

      try {
        json = JSON.parse(data);
      } catch (e) {
        console.error("JSON ERROR:", e);
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
      showAlert("error", "Error", "Error saving supplier");
    });
});

// ================= EDIT SUPPLIER (FILL MODAL) =================
function editSupplier(code) {
  const supplier = suppliers.find(s => s.supplier_code === code);
  if (!supplier) return;

  document.getElementById("edit_supplier_code").value = supplier.supplier_code;
  document.getElementById("edit_supplier_name").value = supplier.supplier_name || "";
  document.getElementById("edit_contact_name").value = supplier.contact_name || "";
  document.getElementById("edit_phone").value = supplier.phone || "";
  document.getElementById("edit_email").value = supplier.email || "";
  document.getElementById("edit_address").value = supplier.address || "";

  document.getElementById("editSupplierModal").style.display = "flex";
  document.body.classList.add("modal-open");
}

// ================= UPDATE SUPPLIER =================
document.getElementById("editSupplierForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/suppliers/update.php", {
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

// ================= DELETE SUPPLIER =================
function deleteSupplier(code) {

  showAlert(
    "confirm",
    "Delete Supplier",
    "Are you sure you want to delete this supplier?",
    "Delete",
    function (confirmed) {
      if (!confirmed) return;

      fetch("http://localhost/POS-GAS/api/suppliers/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          supplier_code: code,
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