<?php

require_once "../../../config/database.php";
require_once "../../../backend/middleware/auth.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAS STATION</title>
  <link rel="stylesheet" href="/POS-GAS/frontend/css/receivables.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css" />
</head>

<body>

  <!-- ========================================================================================================================== -->
  <!--                                                        SIDEBAR                                                             -->
  <!-- ========================================================================================================================== -->

  <div class="sidebar">
    <div>

      <ul class="menu">
        <li onclick="window.location.href='../dashboard';">
          <img src="/POS-GAS/frontend/assets/icons/dashboard-icon.png" class="menu-icon">
          <span>Dashboard</span>
        </li>

        <li onclick="window.location.href='../sales';">
          <img src="/POS-GAS/frontend/assets/icons/sales-icon.png" class="menu-icon">
          <span>Sales</span>
        </li>

        <li onclick="window.location.href='../productspage';">
          <img src="/POS-GAS/frontend/assets/icons/products-icon.png" class="menu-icon">
          <span>Products</span>
        </li>

        <li onclick="window.location.href='../customer';">
          <img src="/POS-GAS/frontend/assets/icons/customer-icon.png" class="menu-icon">
          <span>Customers</span>
        </li>

        <li onclick="window.location.href='../supplier';">
          <img src="/POS-GAS/frontend/assets/icons/supplier-icon.png" class="menu-icon">
          <span>Suppliers</span>
        </li>

        <li class="active">
          <img src="/POS-GAS/frontend/assets/icons/report-icon.png" class="menu-icon">
          <span>Account Recivables Report</span>
        </li>

        <li onclick="window.location.href='../users';">
          <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
          <span>Users</span>
        </li>

        <li onclick="window.location.href='../category';">
          <img src="/POS-GAS/frontend/assets/icons/category-icon.png" class="menu-icon">
          <span>Categories</span>
        </li>

        <li onclick="window.location.href='../pump';">
          <img src="/POS-GAS/frontend/assets/icons/pumps-icon.png" class="menu-icon">
          <span>Pumps</span>
        </li>

        <li onclick="window.location.href='../others';">
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
          <div class="dropdown-item" onclick="othergoToAccount()">Account Settings</div>
          <div class="dropdown-item" onclick="otherlogout()">Logout</div>
        </div>
      </div>
    </div>


    <div class="receivables-container">

      <!-- SEARCH & ACTION BAR -->
      <div class="receivables-controls">
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Search receivables..." />
        </div>



        <button class="btn add-btn" onclick="addProduct()">+ Add</button>
        <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
      </div>

      <!-- TABLE -->
      <div class="table-wrapper">
        <table id="receivablesTable">
          <thead>
            <tr>
              <th>Invoice Number</th>
              <th>Customer Name</th>
              <th>Total Debt</th>
              <th>Total Paid</th>
              <th>Remaining Balance</th>
              <th>Due Date</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody id="tableBody">
            <!-- Dynamic rows -->
          </tbody>

          <tfoot>
            <tr>
              <td colspan="7"></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- PAGINATION CONTROLS -->
      <div class="pagination-container">

        <div class="limit-box">
          Show
          <select id="rowsPerPage" onchange="changeLimit()">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
          </select>
          entries
        </div>

        <div class="pagination">
          <button onclick="prevPage()">Prev</button>
          <span id="pageInfo">Page 1</span>
          <button onclick="nextPage()">Next</button>
        </div>

      </div>

    </div>

  </div>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>
  <script src="/POS-GAS/frontend/js/print.js"></script>
  <script src="/POS-GAS/frontend/js/alert.js"></script>
  <script src="/POS-GAS/frontend/js/dropdown.js"></script>
</body>

</html>