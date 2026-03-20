<?php
session_start();
require_once "../../backend/middleware/route.php";


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
<<<<<<< HEAD
  <!-- <title>JAYLO MEDICAL CLINIC</title> -->
=======
  <title>GAS STATION</title>
>>>>>>> cd15f9baacad89574b7c94d5d103f610a8f965c7
  <link rel="stylesheet" href="/POS-GAS/frontend/css/landing-page.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
</head>

<body>

  <div class="wrapper">

    <!-- Left Card -->
    <div class="clinic-card">
      <div class="logo-box">
        <span>
          <img src="/POS-GAS/frontend/assets/Jaylo.png" alt="Clinic Logo">
        </span>
      </div>
      <h1>JAYLO MEDICAL CLINIC</h1>
    </div>

    <!-- Right Card -->
    <div class="login-card">
      <h2>LOG IN</h2>

      <form id="commit_command">
        <input type="text" id="username" placeholder="Username" name="username" required>
        <input type="password" id="password" placeholder="Password" name="password" required>


        <button type="submit">LOGIN</button>
      </form>
    </div>

  </div>

  <!-- Custom Alert JS -->
  <script src="/POS-GAS/frontend/js/alert.js"></script>


  <script>
    document.getElementById("commit_command").addEventListener("submit", function(event) {
      event.preventDefault();

      const form = event.target;
      const formData = new FormData(form);

      fetch("../../api/auth/login.php", {
          method: "POST",
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          console.log("Server Response:", data);

          if (data.status === "success") {
            showAlert(
              "success",
              "Login Successful!",
              data.message,
              "Continue",
              function() {
                window.location.href = data.redirect;
              }
            );
          } else {
            showAlert(
              "error",
              "Login Failed",
              data.message
            );
          }




        })
        .catch(error => {
          console.error("Error", error);
          showAlert(
            "error",
            "Login Failed",
            "Something went wrong. Try Again"
          );
        })


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

  <script>
    function login() {
      const username = document.getElementById("username").value;
      const password = document.getElementById("password").value;

      if (username === "admin" && password === "admin") {

        showAlert(
          "success",
          "Login Successful!",
          "Welcome Admin!",
          "Continue",
          function() {
            window.location.href = "users.php";
          }
        );

      } else {

        showAlert(
          "error",
          "Login Failed",
          "Invalid Username or Password"
        );

      }
    }
  </script>

 

</body>

</html>