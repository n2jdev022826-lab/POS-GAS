<?php

require_once "../../../config/database.php";
require_once "../../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$products = [];

$sql = "SELECT 
    p.product_code,
    p.name,
    c.category_name AS category,
    s.supplier_name AS supplier,
    p.original_price,
    p.selling_price,
    p.expiry_date,
    p.image,
    p.created_at,

    pil.quantity,
    pm.remaining_quantity AS quantity_left

FROM products p

LEFT JOIN categories c ON p.category_id = c.category_id
LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id

LEFT JOIN (
    SELECT pil1.product_id, pil1.quantity
    FROM product_inventory_logs pil1
    INNER JOIN (
        SELECT product_id, MAX(created_at) AS latest_date
        FROM product_inventory_logs
        GROUP BY product_id
    ) pil2 
    ON pil1.product_id = pil2.product_id 
    AND pil1.created_at = pil2.latest_date
) pil ON p.id = pil.product_id

LEFT JOIN product_monitoring pm 
    ON p.id = pm.product_id

WHERE p.is_deleted = 0
ORDER BY p.created_at DESC";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $products[] = $row;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GAS STATION</title>
  <link rel="stylesheet" href="/POS-GAS/frontend/css/productlist.css">
  <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
</head>

<body>
  <!-- ================= MAIN ================= -->
  <div class="main">

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

        <button class="btn back-btn" onclick="goBack()"><img src="/POS-GAS/frontend/assets/icons/back-icon.png"> Back</button>
        <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
      </div>

      <!-- TABLE -->
      <div class="table-wrapper">
        <table id="productsTable">
          <thead>
            <tr>
              <th>Product Image</th>
              <th>Product Code</th>
              <th>Product Name</th>
              <th>Category</th>
              <th>Supplier</th>
              <th>Date Received</th>
              <th>Expiry Date</th>
              <th>Original Price</th>
              <th>Selling Price</th>
              <th>QTY.</th>
              <th>QTY. Left</th>
              <th>Total Cost</th>
            </tr>
          </thead>

          <tbody id="tableBody">
            <!-- Dynamic rows -->
          </tbody>

          <tfoot>
            <tr>
              <td colspan="12"></td>
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

  <script>
    const products = <?php echo json_encode($products ?? []); ?>;
  </script>

  <script src="/POS-GAS/frontend/js/products.js"></script>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>
  <script src="/POS-GAS/frontend/js/print.js"></script>
  <script src="/POS-GAS/frontend/js/productlist.js"></script>

  <script>
    function goBack() {
      document.body.style.opacity = "0";

      setTimeout(() => {
        if (window.history.length > 1) {
          window.history.back();
        } else {
          window.location.href = "/POS-GAS/";
        }
      }, 100);
    }
  </script>

</body>

</html>