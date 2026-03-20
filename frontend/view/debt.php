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
  <link rel="stylesheet" href="/POS-GAS/frontend/css/debt.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
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

        <li onclick="window.location.href='products.php';">
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

        <li onclick="window.location.href='report.php';">
          <img src="/POS-GAS/frontend/assets/icons/report-icon.png" class="menu-icon">
          <span>Report</span>
        </li>

        <li class="active">
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


   <!-- CONTENTS -->   
  <div class="debt-container">

      <!-- SEARCH & ACTION BAR -->
      <div class="debt-controls">
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Search debt..." />
        </div>



        <button class="btn add-btn" onclick="addProduct()">+ Add</button>
        <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
      </div>

      <!-- TABLE -->
      <div class="table-wrapper">
        <table id="debtTable">
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


  <!-- ================= ADD DEBT MODAL ================= -->

<div id="addDebtModal" class="modal-overlay">

  <div class="modal-box">

    <div class="modal-header">
      <h2>Add Debt</h2>
      <span class="close-btn" onclick="closeDebtModal()">×</span>
    </div>

    <form id="addDebtForm">

      <div class="modal-grid">

        <div class="form-group">
          <label>Invoice Number</label>
          <input type="text" id="invoiceNumber" required>
        </div>

        <div class="form-group">
          <label>Customer Name</label>
          <input type="text" id="customerName" required>
        </div>

        <div class="form-group">
          <label>Total Debt</label>
          <input type="number" id="totalDebt" required>
        </div>

        <div class="form-group">
          <label>Total Paid</label>
          <input type="number" id="totalPaid">
        </div>

        <div class="form-group">
          <label>Due Date</label>
          <input type="date" id="dueDate" required>
        </div>

      </div>

      <div class="modal-actions">
        <button type="submit" class="save-btn">Save Debt</button>
      </div>

    </form>

  </div>

</div>

  <script src="/POS-GAS/frontend/js/date-time.js"></script>
  <script src="/POS-GAS/frontend/js/print.js"></script>
  <script src="/POS-GAS/frontend/js/debt-modal.js"></script>
</body>

</html>