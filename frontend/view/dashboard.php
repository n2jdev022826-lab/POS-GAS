<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

/* ================= WEEKLY SALES ================= */
$weeklySales = [0,0,0,0,0,0,0];

$sqlWeekly = "
SELECT DAYOFWEEK(sale_date) as day, SUM(total_amount) as total 
FROM Sales 
WHERE is_deleted = 0
GROUP BY DAYOFWEEK(sale_date)
";

$resultWeekly = $conn->query($sqlWeekly);

while ($row = $resultWeekly->fetch_assoc()) {
    $index = $row['day'] - 1; // Sunday = 1
    $weeklySales[$index] = (float)$row['total'];
}


/* ================= PIE CHART (TOTAL COST PER CATEGORY) ================= */
$categoryLabels = [];
$categoryData = [];

$sqlPie = "
SELECT 
    category,
    SUM(purchase_price * stock_quantity) AS total_cost
FROM Products
GROUP BY category
";

$resultPie = $conn->query($sqlPie);

while ($row = $resultPie->fetch_assoc()) {
    $categoryLabels[] = $row['category'];
    $categoryData[] = (float)$row['total_cost'];
}

/* ================= DASHBOARD DATA ================= */

// TOTAL SALES
$totalSales = 0;
$sqlSales = "SELECT SUM(total_amount) as total FROM Sales WHERE is_deleted = 0";
$resultSales = $conn->query($sqlSales);
if ($row = $resultSales->fetch_assoc()) {
    $totalSales = $row['total'] ?? 0;
}

// TOTAL PRODUCTS
$totalProducts = 0;
$sqlProducts = "SELECT COUNT(*) as total FROM Products";
$resultProducts = $conn->query($sqlProducts);
if ($row = $resultProducts->fetch_assoc()) {
    $totalProducts = $row['total'];
}

// TOTAL CUSTOMERS
$totalCustomers = 0;
$sqlCustomers = "SELECT COUNT(*) as total FROM Customers";
$resultCustomers = $conn->query($sqlCustomers);
if ($row = $resultCustomers->fetch_assoc()) {
    $totalCustomers = $row['total'];
}

// WEEKLY SALES (Sun-Sat)
$weeklySales = [0,0,0,0,0,0,0];

$sqlWeekly = "
SELECT DAYOFWEEK(sale_date) as day, SUM(total_amount) as total 
FROM Sales 
WHERE is_deleted = 0
GROUP BY DAYOFWEEK(sale_date)
";

$resultWeekly = $conn->query($sqlWeekly);

while ($row = $resultWeekly->fetch_assoc()) {
    $index = $row['day'] - 1; // MySQL Sunday = 1
    $weeklySales[$index] = (float)$row['total'];
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>GAS STATION</title>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="/POS-GAS/frontend/css/dashboard.css" />
</head>

<body>

  <!-- ================= SIDEBAR ================= -->

  <div class="sidebar">
    <div>

      <ul class="menu">
        <li class="active">
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

    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"></div>
      </div>
    </div>

    <!-- Cards -->
    <div class="cards">
  <div class="card">
    <h3 id="total-sales">TOTAL SALES</h3>
    <h1>₱ <?php echo number_format($totalSales, 2); ?></h1>
  </div>

  <div class="card">
    <h3 id="total-products">TOTAL PRODUCTS</h3>
    <h1><?php echo $totalProducts; ?></h1>
  </div>

  <div class="card">
    <h3 id="total-customers">TOTAL CUSTOMERS</h3>
    <h1><?php echo $totalCustomers; ?></h1>
  </div>
</div>

    <!-- Charts -->
    <div class="charts">
      <div id="line" class="chart-box">
        <h3>Sales</h3>
        <canvas id="lineChart"></canvas>
      </div>

      <div id="pie" class="chart-box">
        <h3>Expenses</h3>
        <canvas id="pieChart"></canvas>
      </div>
    </div>

  </div>

<script>
  const weeklySales = <?php echo json_encode($weeklySales); ?>;
  const pieLabels = <?php echo json_encode($categoryLabels); ?>;
  const pieData = <?php echo json_encode($categoryData); ?>;
</script>

<script src="/POS-GAS/frontend/js/dashboard.js"></script>
  <script src="/POS-GAS/frontend/js/date-time.js"></script>


</body>

</html>