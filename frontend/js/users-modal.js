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
  alert("Edit user: " + userCode);

  // diri nako e butang edit modal puhon unahon sa nakog balhin tanan design
}

// DELETE USER (error pani kay wla pako nag butang ug delete nga api create plng)
function deleteUser(userCode) {
  if (confirm("Are you sure you want to delete this user?")) {
    fetch("/POS-GAS/api/users/delete", {
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
        alert(data.message);

        if (data.status === "success") {
          location.reload();
        }
      })
      .catch((err) => {
        console.error(err);
        alert("Error deleting user");
      });
  }
}
