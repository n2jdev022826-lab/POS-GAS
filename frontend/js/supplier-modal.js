// OPEN MODAL
function addSupplier() {
  const modal = document.getElementById("addSupplierModal");

  modal.style.display = "flex";
  document.body.classList.add("modal-open");
}

// CLOSE MODAL
function closeSupplierModal() {
  const modal = document.getElementById("addSupplierModal");

  modal.style.display = "none";
  document.body.classList.remove("modal-open");
}

// ADD SUPPLIER FORM SUBMIT
document
  .getElementById("addSupplierForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("/POS-GAS/api/suppliers/create", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        console.log("Server Response:", data);

        if (data.status === "success") {
          showAlert(
            "success",
            "Category Added",
            data.message,
            "OK",
            function () {
              window.location.reload();
            },
          );
        } else {
          showAlert("error", "Login Failed", data.message);
        }
      })
      .catch((err) => {
        console.error(err);
        alert("Error saving Supplier");
      });
  });
