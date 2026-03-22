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
  <title>GAS STATION</title>
  <link rel="stylesheet" href="/POS-GAS/frontend/css/productpage.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">

</head>

<body>
  <!-- ================= SIDEBAR ================= -->

  <div class="sidebar">
    <div>

      <ul class="menu">
        <li onclick="window.location.href='dashboard.php';">
          <img src="/POS-GAS/frontend/assets/icons/dashboard-icon.png" class="menu-icon">
          <span>Dashboard</span>
        </li>

        <li onclick="window.location.href='sales.php';">
          <img src="/POS-GAS/frontend/assets/icons/sales-icon.png" class="menu-icon">
          <span>Sales</span>
        </li>

        <li class="active">
          <img src="/POS-GAS/frontend/assets/icons/products-icon.png" class="menu-icon">
          <span>Products</span>
        </li>

        <li onclick="window.location.href='customer.php';">
          <img src="/POS-GAS/frontend/assets/icons/customer-icon.png" class="menu-icon">
          <span>Customers</span>
        </li>

        <li onclick="window.location.href='supplier.php';">
          <img src="/POS-GAS/frontend/assets/icons/supplier-icon.png" class="menu-icon">
          <span>Suppliers</span>
        </li>

        <li onclick="window.location.href='report.php'">
          <img src="/POS-GAS/frontend/assets/icons/report-icon.png" class="menu-icon">
          <span>Report</span>
        </li>

        <li onclick="window.location.href='debt.php';">
          <img src="/POS-GAS/frontend/assets/icons/debt-icon.png" class="menu-icon">
          <span>Manage Debts</span>
        </li>


        <li onclick="window.location.href='users.php';">
          <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
          <span>Users</span>
        </li>

        <li onclick="window.location.href='tracker.php';">
          <img src="/POS-GAS/frontend/assets/icons/tracker-icon.png" class="menu-icon">
          <span>Track Supplies</span>
        </li>

      </ul>
    </div>
    <div class="logout" onclick="window.location.href='session';">
      <img src="/POS-GAS/frontend/assets/icons/logout-icon.png" class="menu-icon">
      LOG OUT
    </div>
  </div>


  <!-- ================= MAIN ================= -->
  <div class="main">

    <!-- TOP BAR -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
      </div>
    </div>


    <!-- Cards -->
    <div class="cards">
      <div id="salescard" class="card" data-bg="FUEL"
        onclick="window.location.href='fuel';">
        <h3>FUEL PRODUCTS</h3>
        <h1>Desiel, Gasoline, Kerosin</h1>
      </div>

      <div id="receivablescard" class="card" data-bg="MARK"
        onclick="window.location.href='products';">
        <h3>OTHER PRODUCTS</h3>
        <h1>Other Available Products</h1>
      </div>
    </div>

  </div>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>
</body>

</html>