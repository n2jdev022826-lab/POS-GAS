<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

/* ================= TODAY SALES ================= */
$todaySales = 0;

$sqlToday = "
SELECT 
    (SELECT IFNULL(SUM(total_amount),0) FROM transactions WHERE DATE(created_at) = CURDATE()) +
    (SELECT IFNULL(SUM(total_amount),0) FROM product_sales WHERE DATE(created_at) = CURDATE())
    AS total
";

$resToday = $conn->query($sqlToday);
if ($row = $resToday->fetch_assoc()) {
  $todaySales = $row['total'];
}


/* ================= MONTHLY SALES ================= */
$monthlySales = 0;

$sqlMonth = "
SELECT 
    (SELECT IFNULL(SUM(total_amount),0) FROM transactions WHERE MONTH(created_at) = MONTH(CURDATE())) +
    (SELECT IFNULL(SUM(total_amount),0) FROM product_sales WHERE MONTH(created_at) = MONTH(CURDATE()))
    AS total
";

$resMonth = $conn->query($sqlMonth);
if ($row = $resMonth->fetch_assoc()) {
  $monthlySales = $row['total'];
}


/* ================= TOTAL FUEL STOCK ================= */
$totalFuelStock = 0;

$sqlFuelStock = "
SELECT SUM(remaining_liters) as total FROM fuel_monitoring
";

$resFuel = $conn->query($sqlFuelStock);
if ($row = $resFuel->fetch_assoc()) {
  $totalFuelStock = $row['total'] ?? 0;
}


/* ================= LOW FUEL ALERT ================= */
$lowFuelCount = 0;

$sqlLowFuel = "
SELECT COUNT(*) as total 
FROM fuel_monitoring 
WHERE remaining_liters < 500
";

$resLowFuel = $conn->query($sqlLowFuel);
if ($row = $resLowFuel->fetch_assoc()) {
  $lowFuelCount = $row['total'];
}

$query = "
  SELECT f.name, fm.remaining_liters
  FROM fuel_monitoring fm
  LEFT JOIN fuels f ON f.id = fm.fuel_id
";

$result = mysqli_query($conn, $query);

if (!$result) {
  die("Error: " . mysqli_error($conn));
}


/* ================= TOTAL DEBTS ================= */
$totalDebt = 0;

$sqlDebt = "
SELECT IFNULL(SUM(balance),0) as total 
FROM debts 
WHERE status = 'unpaid' 
AND is_deleted = 0
";

$resDebt = $conn->query($sqlDebt);
if ($row = $resDebt->fetch_assoc()) {
  $totalDebt = $row['total'];
}

/* ================= TOTAL SALES ================= */
/* Fuel transactions + product sales */
$totalSales = 0;

$sqlSales = "
SELECT 
    (SELECT IFNULL(SUM(total_amount),0) FROM transactions WHERE is_deleted = 0) +
    (SELECT IFNULL(SUM(total_amount),0) FROM product_sales WHERE is_deleted = 0)
    AS total
";

$resultSales = $conn->query($sqlSales);
if ($row = $resultSales->fetch_assoc()) {
  $totalSales = $row['total'];
}

/* ================= TOTAL PRODUCTS ================= */
$totalProducts = 0;

$sqlProducts = "SELECT COUNT(*) as total FROM products WHERE is_deleted = 0";
$resultProducts = $conn->query($sqlProducts);

if ($row = $resultProducts->fetch_assoc()) {
  $totalProducts = $row['total'];
}

/* ================= TOTAL CUSTOMERS ================= */
/* No customers table → using users with role cashier */
$totalCustomers = 0;

$sqlCustomers = "SELECT COUNT(*) as total FROM customers WHERE is_deleted = 0";
$resultCustomers = $conn->query($sqlCustomers);

if ($row = $resultCustomers->fetch_assoc()) {
  $totalCustomers = $row['total'];
}

/* ================= WEEKLY SALES ================= */
/* Combine transactions + product sales */
$weeklySales = [0, 0, 0, 0, 0, 0, 0];

$sqlWeekly = "
SELECT DAYOFWEEK(created_at) as day, SUM(total_amount) as total 
FROM (
    SELECT created_at, total_amount FROM transactions WHERE is_deleted = 0
    UNION ALL
    SELECT created_at, total_amount FROM product_sales WHERE is_deleted = 0
) AS combined_sales
GROUP BY DAYOFWEEK(created_at)
";

$resultWeekly = $conn->query($sqlWeekly);

while ($row = $resultWeekly->fetch_assoc()) {
  $index = $row['day'] - 1; // Sunday = 1
  $weeklySales[$index] = (float)$row['total'];
}

/* ================= PIE CHART (TOTAL COST = price * stock) ================= */

$categoryLabels = [];
$categoryData = [];

$sqlPie = "
SELECT 
    category,
    SUM(price * stock) AS total_cost
FROM products
WHERE is_deleted = 0
GROUP BY category
";

$resultPie = $conn->query($sqlPie);

while ($row = $resultPie->fetch_assoc()) {
  $categoryLabels[] = $row['category'];
  $categoryData[] = (float)$row['total_cost'];
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
  <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css" />
  <link rel="stylesheet" href="/POS-GAS/frontend/css/dashboard.css" />
</head>

<body>

  <!-- ========================================================================================================================== -->
  <!--                                                        SIDEBAR                                                             -->
  <!-- ========================================================================================================================== -->

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

        <li onclick="window.location.href='productspage.php';">
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

  <!--==========================================================================================================================-->
  <!--                                                   MAIN CONTENT                                                             -->
  <!-- ========================================================================================================================== -->

  <div class="main">


    <!-- ========================================================================================================================== -->
    <!--                                                         TOPBAR                                                             -->
    <!-- ========================================================================================================================== -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
      </div>
    </div>

    <!-- ========================================================================================================================== -->
    <!--                                                        CARDS                                                               -->
    <!-- ========================================================================================================================== -->
    <div class="cards">

      <div class="card">
        <h3>TOTAL SALES</h3>
        <h1>₱ <?php echo number_format($totalSales, 2); ?></h1>
      </div>

      <div class="card">
        <h3>TODAY SALES</h3>
        <h1>₱ <?php echo number_format($todaySales, 2); ?></h1>
      </div>

      <div class="card">
        <h3>MONTH SALES</h3>
        <h1>₱ <?php echo number_format($monthlySales, 2); ?></h1>
      </div>

      <div class="card">
        <h3>FUEL STOCK</h3>
        <h1><?php echo number_format($totalFuelStock, 2); ?> L</h1>
      </div>

      <div class="card <?php echo $lowFuelCount > 0 ? 'alert' : ''; ?>">
        <h3>LOW FUEL ALERT</h3>
        <h1><?php echo $lowFuelCount; ?></h1>
      </div>

      <div class="card">
        <h3>PENDING DEBTS</h3>
        <h1>₱ <?php echo number_format($totalDebt, 2); ?></h1>
      </div>

      <div class="card">
        <h3>PRODUCTS</h3>
        <h1><?php echo $totalProducts; ?></h1>
      </div>

      <div class="card">
        <h3>CUSTOMERS</h3>
        <h1><?php echo $totalCustomers; ?></h1>
      </div>

    </div>

    <!-- ========================================================================================================================== -->
    <!--                                                        CHARTS                                                              -->
    <!-- ========================================================================================================================== -->
    <div class="charts">
      <div id="line" class="chart-box">
        <h3>Sales</h3>
        <canvas id="lineChart"></canvas>
      </div>

      <div id="pie" class="chart-box">
        <h3>Fuel Tank Levels</h3>

        <div class="tank-grid">
          <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <div class="tank-card">

              <div class="tank">
                <div class="fuel"
                  data-liters="<?php echo $row['remaining_liters']; ?>">
                  <div class="wave"></div>
                  <div class="bubbles"></div>
                </div>
              </div>

              <div class="tank-label">
                <h4><?php echo $row['name']; ?></h4>
                <p><?php echo number_format($row['remaining_liters'], 2); ?> L</p>
                <span class="percent">0%</span>
              </div>

            </div>
          <?php } ?>
        </div>
      </div>

    </div>

    <!-- ========================================================================================================================== -->
    <!--                                                        SCRIPTS                                                             -->
    <!-- ========================================================================================================================== -->
    <script>
      const weeklySales = <?php echo json_encode($weeklySales); ?>;
      const pieLabels = <?php echo json_encode($categoryLabels); ?>;
      const pieData = <?php echo json_encode($categoryData); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/dashboard.js"></script>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>

   


</body>

</html>