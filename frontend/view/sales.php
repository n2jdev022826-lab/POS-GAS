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
  <link rel="stylesheet" href="/POS-GAS/frontend/css/sales.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
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
        <li onclick="window.location.href='dashboard';">
          <img src="/POS-GAS/frontend/assets/icons/dashboard-icon.png" class="menu-icon">
          <span>Dashboard</span>
        </li>

        <li class="active" onclick="window.location.href='sales';">
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

        <li onclick="window.location.href='others';">
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
              <th>Category</th>
              <th>Price</th>
              <th>Qty</th>
              <th>Amount</th>
              <th>Profit</th>
              <th>Action</th>
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
              <td></td>
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

  <!-- EDIT MODAL -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <h3>Edit Quantity</h3>
      <input type="number" id="editQty" min="1" />
      <div class="modal-actions">
        <button onclick="saveEdit()">Save</button>
        <button onclick="closeModal()">Cancel</button>
      </div>
    </div>
  </div>

  <!-- UNDO TOAST -->
  <div id="undoToast" class="undo-toast">
    Item deleted
    <button onclick="undoDelete()">Undo</button>
  </div>

  <script src="/POS-GAS/frontend/js/sales.js"></script>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>
  <script src="/POS-GAS/frontend/js/print.js"></script>
  <script src="/POS-GAS/frontend/js/search.js"></script>
  <script src="/POS-GAS/frontend/js/alert.js"></script>
  <script src="/POS-GAS/frontend/js/dropdown.js"></script>

</body>

</html>