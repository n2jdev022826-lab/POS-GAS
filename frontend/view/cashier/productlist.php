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
  <link rel="stylesheet" href="/POS-GAS/frontend/css/productlist.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
</head>

<body>
  <!-- ================= MAIN ================= -->
  <div class="main">

    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname']. ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"></div>
      </div>
    </div>

    <!-- PRODUCT STATS / FILTER CARDS -->
<div class="product-stats">

  <div class="stat-card" onclick="filterProducts('expiring')">
    <span>Nearly Expire Products:</span>
    <b id="expiringCount">0</b>
  </div>

  <div class="stat-card" onclick="filterProducts('all')">
    <span>Total No. of Products:</span>
    <b id="totalProducts">0</b>
  </div>

  <div class="stat-card" onclick="filterProducts('lowstock')">
    <span>Products are below QTY of 10:</span>
    <b id="lowStockCount">0</b>
  </div>

</div>

    <div class="produt-container">

      <!-- SEARCH & ACTION BAR -->
      <div class="product-controls">
        <div class="search-box">
          <input type="text" id="searchInput" placeholder="Search product..." />
        </div>

        <button class="btn add-btn" onclick="goBack()">👈 Back</button>
        <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
      </div>

      <!-- TABLE -->
      <div class="table-wrapper">
        <table id="salesTable">
          <thead>
            <tr>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Generic Name</th>
              <th>Category</th>
              <th>Supplier</th>
              <th>Date Received</th>
              <th>Expiry Date</th>
              <th>Original Price</th>
              <th>Selling Price</th>
              <th>QTY.</th>
              <th>QTY. Left</th>
              <th>Total</th>
            </tr>
          </thead>

          <tbody id="tableBody">
            <!-- Dynamic rows -->
          </tbody>

          <tfoot>
            <tr>
              <td colspan="12"></td>

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

  </div>
  
  <script src="/POS-GAS/frontend/js/products.js"></script>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>
  <script src="/POS-GAS/frontend/js/print.js"></script>


  <script>
      function goBack() {
        document.body.style.opacity = "0";

        setTimeout(() => {
          if (window.history.length > 1) {
            window.history.back();
          } else {
            window.location.href = "/POS-GAS/";
          }
        }, 500);
      }
    </script>

</body>

</html>