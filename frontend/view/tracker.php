<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JAYLO MEDICAL CLINIC</title>
  <link rel="stylesheet" href="/POS/FRONT-END/css/report.css">
  <link rel="stylesheet" href="/POS/FRONT-END/css/print.css">
</head>

<body>
<!-- ================= SIDEBAR ================= -->

  <div class="sidebar">
    <div>
      
      <ul class="menu">
        <li onclick="window.location.href='dashboard.php';">
          <img src="/POS/FRONT-END/assets/icons/dashboard-icon.png" class="menu-icon">
          <span>Dashboard</span>
        </li>

        <li onclick="window.location.href='sales.php';">
          <img src="/POS/FRONT-END/assets/icons/sales-icon.png" class="menu-icon">
          <span>Sales</span>
        </li>

        <li onclick="window.location.href='products.php';">
          <img src="/POS/FRONT-END/assets/icons/products-icon.png" class="menu-icon">
          <span>Products</span>
        </li>

        <li onclick="window.location.href='customer.php';">
          <img src="/POS/FRONT-END/assets/icons/customer-icon.png" class="menu-icon">
          <span>Customers</span>
        </li>

        <li onclick="window.location.href='supplier.php';">
          <img src="/POS/FRONT-END/assets/icons/supplier-icon.png" class="menu-icon">
          <span>Suppliers</span>
        </li>

        <li onclick="window.location.href='report.php';">
          <img src="/POS/FRONT-END/assets/icons/report-icon.png" class="menu-icon">
          <span>Report</span>
        </li>

        <li onclick="window.location.href='debt.php';">
          <img src="/POS/FRONT-END/assets/icons/debt-icon.png" class="menu-icon">
          <span>Manage Debts</span>
        </li>

        
        <li onclick="window.location.href='users.php';">
         <img src="/POS/FRONT-END/assets/icons/user-icon.png" class="menu-icon">
          <span>Users</span>
        </li>

        <li class="active">
          <img src="/POS/FRONT-END/assets/icons/tracker-icon.png" class="menu-icon">
          <span>Track Supplies</span>
        </li>

      </ul>
    </div>
        <div class="logout" onclick="window.location.href='session';">
      <img src="/POS/FRONT-END/assets/icons/logout-icon.png" class="menu-icon"> 
      LOG OUT
    </div>
  </div>


  <!-- ================= MAIN ================= -->
  <div class="main">

    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
         <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"></div>
      </div>
    </div>

    
  </div>
  <script src="/POS/FRONT-END/js/date-time.js"></script>
</body>

</html>