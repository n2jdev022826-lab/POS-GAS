function openModal() {
  const modal = document.getElementById("customerModal");
  modal.style.display = "flex";
  document.body.classList.add("modal-open"); // optional: prevent scrolling
}

function closeModal() {
  const modal = document.getElementById("customerModal");
  modal.style.display = "none";
  document.body.classList.remove("modal-open");
}

// Get all phone inputs
const phoneInputs = document.querySelectorAll('input[name="phone"]');

phoneInputs.forEach((input) => {
  input.addEventListener("input", function () {
    // Remove any non-digit character
    this.value = this.value.replace(/\D/g, "");

    // Limit to 11 digits
    if (this.value.length > 11) {
      this.value = this.value.slice(0, 11);
    }
  });
});

// ================= ADD CUSTOMER =================
document.getElementById("addCustomer").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/customer/create.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.json())
    .then((data) => {
      showAlert("success", "Success", data.message);

      if (data.status === "success") {
        setTimeout(() => location.reload(), 1000);
      } else {
        showAlert("error", "Failed", data.message);
      }
    })
    .catch((err) => {
      console.error(err);
      showAlert("error", "Failed", "Something went wrong!");
    });
});

// ================= DELETE =================
function deleteCustomer(code) {
  showAlert(
    "confirm",
    "Delete Customer",
    "Are you sure you want to delete this customer?",
    "Delete",
    function (confirmed) {
      if (!confirmed) return;

      const formData = new FormData();
      formData.append("customer_code", code);

      fetch("http://localhost/POS-GAS/api/customer/delete.php", {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.status === "success") {
            showAlert("success", "Success", data.message);
            setTimeout(() => location.reload(), 1000);
          } else {
            showAlert("error", "Failed", data.message);
          }
        })
        .catch((err) => {
          console.error(err);
          showAlert("error", "Failed", "Something went wrong!");
        });
    }
  );
}
// ================= EDIT MODAL =================
function openEditModal(customer) {
  document.getElementById("editCustomerModal").style.display = "flex";

  document.getElementById("edit_customer_code").value = customer.customer_code;
  document.getElementById("edit_customer_name").value = customer.customer_name;
  document.getElementById("edit_phone").value = customer.phone;
  document.getElementById("edit_email").value = customer.email;
  document.getElementById("edit_address").value = customer.address;
}

function closeEditModal() {
  document.getElementById("editCustomerModal").style.display = "none";
}

// ================= UPDATE =================
document
  .getElementById("editCustomerForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/customer/update.php", {
      method: "POST",
      body: formData,
    })
      .then((res) => res.json())
      .then((data) => {
        showAlert("success", "Success", data.message);

        if (data.status === "success") {
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert("error", "Failed", data.message);
        }
      })
      .catch((err) => {
        console.error(err);
        showAlert("error", "Failed", "Something went wrong!");
      });
  });
