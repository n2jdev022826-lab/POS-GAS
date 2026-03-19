<?php

require_once "../../../config/database.php";
require_once "../../../backend/middleware/auth.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>JAYLO MEDICAL CLINIC</title>
    <link rel="stylesheet" href="/POS-GAS/frontend/css/salesreport.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
</head>

<body>
  <!-- ================= SIDEBAR ================= -->

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

        <li onclick="window.location.href='../products';">
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
          <span>Sales Report</span>
        </li>

        <li onclick="window.location.href='../debt';">
          <img src="/POS-GAS/frontend/assets/icons/debt-icon.png" class="menu-icon">
          <span>Manage Debts</span>
        </li>


        <li onclick="window.location.href='../users';">
          <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
          <span>Users</span>
        </li>

        <li onclick="window.location.href='../tracker';">
          <img src="/POS-GAS/frontend/assets/icons/tracker-icon.png" class="menu-icon">
          <span>Track Supplies</span>
        </li>

      </ul>
    </div>
    <div class="logout" onclick="window.location.href='index.php';">
      <img src="/POS-GAS/frontend/assets/icons/logout-icon.png" class="menu-icon">
      LOG OUT
    </div>
  </div>

  <!-- ================= MAIN ================= -->
  <div class="main">

    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname']. ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"></div>
      </div>
    </div>


    <div class="sales-container">

      <!-- SEARCH & ACTION BAR -->
      <div class="sales-controls">
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Search product..." />
        </div>



        <button class="btn add-btn" onclick="addProduct()">+ Add</button>
        <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
      </div>

      <!-- TABLE -->
      <div class="table-wrapper">
        <table id="salesTable">
          <thead>
            <tr>
              <th>Sales Code</th>
              <th>Product Name</th>
              <th>Generic Name</th>
              <th>Category/Description</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Amount</th>
              <th>Profit</th>
            </tr>
          </thead>

          <tbody id="tableBody">
            <!-- Dynamic rows -->
          </tbody>

          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td><strong>Total:</strong></td>
              <td id="totalAmount">0.00</td>
              <td id="totalProfit">0.00</td>
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
</body>

</html>