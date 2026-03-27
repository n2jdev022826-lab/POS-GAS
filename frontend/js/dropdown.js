/* DROPDOWN */
const employeeMenu = document.getElementById("employeeMenu");
const dropdown = document.getElementById("employeeDropdown");
employeeMenu.addEventListener("click", (e) => {
  e.stopPropagation();
  dropdown.style.display = dropdown.style.display === "flex" ? "none" : "flex";
});
document.addEventListener("click", () => {
  dropdown.style.display = "none";
});

/* NAVIGATION */
function goToAccount() {
  window.location.href = "account-settings.php";
}

function logout() {
  showAlert(
    "confirm",
    "Logout",
    "Are you sure you want to log out?",
    "Yes, Logout",
    function (confirmed) {
      if (confirmed) {
        window.location.href = "session";
      }
    }
  );
}
  /* NAVIGATION for foldered files*/
function othergoToAccount() {
  window.location.href = "../account-settings.php";
}

function otherlogout() {
  showAlert(
    "confirm",
    "Logout",
    "Are you sure you want to log out?",
    "Yes, Logout",
    function (confirmed) {
      if (confirmed) {
        window.location.href = "../session";
      }
    }
  );
}