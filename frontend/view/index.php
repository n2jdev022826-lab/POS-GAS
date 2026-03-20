<?php
session_start();


// ✅ If already logged in, redirect manually (SAFE)
if (isset($_SESSION['user_id'])) {
  switch ($_SESSION['role']) {
    case 'admin':
      header("Location: /POS-GAS/frontend/view/dashboard.php");
      exit;
    case 'staff':
      header("Location: /POS-GAS/frontend/view/testing.fuel.php");
      exit;
    case 'cashier':
      header("Location: /POS-GAS/frontend/view/cashier/cashier.php");
      exit;
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>GAS STATION</title>

  <link rel="stylesheet" href="/POS-GAS/frontend/css/landing-page.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
</head>

<body>

  <div class="wrapper">

    <!-- Left Card -->
    <div class="clinic-card">
      <div class="logo-box">
        <span>
          <img src="/POS-GAS/frontend/assets/gas.png" alt="Logo">
        </span>
      </div>
      <h1>NAME</h1>
      <h3>GAS STATION</h3>
    </div>

    <!-- Right Card -->
    <div class="login-card">
      <h2>LOG IN</h2>

      <form id="commit_command">
        <input type="text" id="username" placeholder="Username" required>
        <input type="password" id="password" placeholder="Password" required>
        <button type="submit">LOGIN</button>
      </form>
    </div>

  </div>

  <!-- Alert JS -->
  <script src="/POS-GAS/frontend/js/alert.js"></script>

  <script>
  document.addEventListener("DOMContentLoaded", function () {

    console.log("JS LOADED");

    const form = document.getElementById("commit_command");

    form.addEventListener("submit", function (event) {
      event.preventDefault();

      console.log("FORM SUBMITTED");

      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;

      fetch("http://localhost/POS-GAS/api/auth/login.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json"
        },
        body: JSON.stringify({
          username: username,
          password: password
        })
      })
      .then(res => res.text())
      .then(data => {
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
          showAlert(
            "success",
            "Login Successful",
            json.message,
            "Continue",
            function () {
              window.location.href = json.redirect;
            }
          );
        } else {
          showAlert("error", "Login Failed", json.message);
        }
      })
      .catch(err => {
        console.error("Fetch Error:", err);
        showAlert("error", "Error", "Something went wrong");
      });

    });

  });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {

      <?php if (isset($_SESSION['error'])) { ?>

        showAlert(
          "warning",
          "Access Denied",
          "<?php echo $_SESSION['error']; ?>"
        );

      <?php unset($_SESSION['error']);
      } ?>

    });
  </script>

</body>
</html>