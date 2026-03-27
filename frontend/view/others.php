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
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/others.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
</head>

<body>
  <!-- ========================================================================================================================== -->
  <!--                                                        SIDEBAR                                                             -->
  <!-- ========================================================================================================================== -->

  <div class="sidebar">
    <div>

      <ul class="menu">
        <li onclick="window.location.href='dashboard';">
          <img src="/POS-GAS/frontend/assets/icons/dashboard-icon.png" class="menu-icon">
          <span>Dashboard</span>
        </li>

        <li onclick="window.location.href='sales';">
          <img src="/POS-GAS/frontend/assets/icons/sales-icon.png" class="menu-icon">
          <span>Sales</span>
        </li>

        <li onclick="window.location.href='productspage';">
          <img src="/POS-GAS/frontend/assets/icons/products-icon.png" class="menu-icon">
          <span>Products</span>
        </li>

        <li onclick="window.location.href='customer';">
          <img src="/POS-GAS/frontend/assets/icons/customer-icon.png" class="menu-icon">
          <span>Customers</span>
        </li>

        <li onclick="window.location.href='supplier';">
          <img src="/POS-GAS/frontend/assets/icons/supplier-icon.png" class="menu-icon">
          <span>Suppliers</span>
        </li>

        <li onclick="window.location.href='report';">
          <img src="/POS-GAS/frontend/assets/icons/report-icon.png" class="menu-icon">
          <span>Reports</span>
        </li>

        <li onclick="window.location.href='users';">
          <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
          <span>Users</span>
        </li>

        <li onclick="window.location.href='category';">
          <img src="/POS-GAS/frontend/assets/icons/category-icon.png" class="menu-icon">
          <span>Categories</span>
        </li>

        <li onclick="window.location.href='pump';">
          <img src="/POS-GAS/frontend/assets/icons/pumps-icon.png" class="menu-icon">
          <span>Pumps</span>
        </li>

        <li class="active">
          <img src="/POS-GAS/frontend/assets/icons/settings-icon.png" class="menu-icon">
          <span>Others</span>
        </li>

      </ul>
    </div>

  </div>


  <!-- ================= MAIN ================= -->
  <div class="main">

    <!-- ========================================================================================================================== -->
    <!--                                                        TOPBAR                                                             -->
    <!-- ========================================================================================================================== -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info" id="employeeMenu">
        <div class="employee-name">
          <?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?>
        </div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>

        <!-- DROPDOWN -->
        <div class="employee-dropdown" id="employeeDropdown">
          <div class="dropdown-item" onclick="goToAccount()">Account Settings</div>
          <div class="dropdown-item" onclick="logout()">Logout</div>
        </div>
      </div>
    </div>



        <!-- Cards -->
        <div class="cards">
            <div id="debtcard" class="card" data-bg="DEBTS"
                onclick="window.location.href='debt';">
                <h3>Liabilities</h3>
                <h1>Customer Debts</h1>
            </div>

            <div id="trackcard" class="card" data-bg="TRACK"
                onclick="window.location.href='tracker';">
                <h3>Tracking</h3>
                <h1>Track Supplies</h1>
            </div>

            <div id="ledgercard" class="card" data-bg="LEDGE"
                onclick="window.location.href='#';">
                <h3>Ledger</h3>
                <h1>Customer Ledger</h1>
            </div>
        </div>

    </div>


    </div>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/dropdown.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
</body>

</html>