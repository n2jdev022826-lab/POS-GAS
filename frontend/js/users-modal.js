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

// ADD USER FORM SUBMIT
document.getElementById("addUserForm").addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);

  fetch("/POS-GAS/api/users/create", {
    method: "POST",
    body: formData,
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
      alert("Error saving user");
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
