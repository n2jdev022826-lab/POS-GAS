// OPEN MODAL
function addUsers() {
  const modal = document.getElementById("addUserModal");

  modal.style.display = "flex";
  document.body.classList.add("modal-open");
}

// CLOSE MODAL
function closeUserModal() {
  const modal = document.getElementById("addUserModal");

  modal.style.display = "none";
  document.body.classList.remove("modal-open");
}

function closeEditModal() {
  document.getElementById("editUserModal").style.display = "none";
  document.body.classList.remove("modal-open");
}

// DISPLAY IMAGE PREVIEW IN MODAL
document.getElementById("imageInput").addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      document.getElementById("imagePreview").style.backgroundImage = `url(${e.target.result})`;
    };

    reader.readAsDataURL(file);
  }
});

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

document.getElementById("editImageInput").addEventListener("change", function () {
  const file = this.files[0];

  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      document.getElementById("editImagePreview").style.backgroundImage =
        `url(${e.target.result})`;
    };

    reader.readAsDataURL(file);
  }
});

// ADD USER FORM SUBMIT
document.getElementById("addUserForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/users/create.php", {
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

        // optional: auto reload after 2 seconds
        setTimeout(() => {
          location.reload();
        }, 1000);

      } else {
        showAlert("error", "Failed", json.message);
      }
    })
    .catch((err) => {
      console.error("Fetch Error:", err);
      showAlert("error", "Error", "Error saving user");
    });
});

// EDIT USER
function editUser(userCode) {

  const user = users.find(u => u.user_code === userCode);

  if (!user) return;

  // Fill fields
  document.getElementById("edit_user_code").value = user.user_code;
  document.getElementById("edit_firstname").value = user.fname || "";
  document.getElementById("edit_middlename").value = user.middlename || "";
  document.getElementById("edit_lastname").value = user.lname || "";
  document.getElementById("edit_role").value = user.role || "";
  document.getElementById("edit_phone").value = user.phone || "";
  document.getElementById("edit_sex").value = user.sex || "";
  document.getElementById("edit_address").value = user.address || "";
  document.getElementById("edit_email").value = user.email || "";
  document.getElementById("edit_username").value = user.username || "";
  document.getElementById("edit_birth_date").value = user.date_of_birth || "";
  document.getElementById("edit_hire_date").value = user.hire_date || "";

  // Image preview
  document.getElementById("editImagePreview").style.backgroundImage =
    `url(/POS-GAS/frontend/assets/uploads/users/${user.image || 'default.jpg'})`;

  // Open modal
  document.getElementById("editUserModal").style.display = "flex";
  document.body.classList.add("modal-open");
}


// EDIT USER SUBMIT FORM
document.getElementById("editUserForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("http://localhost/POS-GAS/api/users/update.php", {
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

function deleteUser(userCode) {

  // 🚫 BLOCK SELF DELETE
   if (userCode === window.CURRENT_USER_CODE) {
    showAlert(
      "warning",
      "Action Blocked",
      "You cannot delete your own account while logged in."
    );
    return;
  }

  // ✅ NORMAL DELETE FLOW
  showAlert(
    "confirm",
    "Delete User",
    "Are you sure you want to delete this user?",
    "Delete",
    function (confirmed) {
      if (!confirmed) return;

      fetch("http://localhost/POS-GAS/api/users/delete.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          user_code: userCode,
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