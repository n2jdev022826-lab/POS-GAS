<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$fuels = [];

$sql = "SELECT 
    f.id,
    f.fuel_code,
    f.name,
    f.price_per_liter,
    f.created_at,
    IFNULL(m.remaining_liters, 0) AS remaining_liters
FROM fuels f
LEFT JOIN fuel_monitoring m ON m.fuel_id = f.id
WHERE f.is_deleted = 0";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $fuels[] = $row;
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAS STATION - FUELS</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/users.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">

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
                    <span>Fuels</span>
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

     <!-- TOP BAR -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
        </div>
      </div>

        <!-- TABLE -->
        <div class="user-container">

            <div class="user-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search fuel..." />
                </div>

                <button class="btn add-btn" onclick="addFuel()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <div class="table-wrapper">

                <table id="fuelTable">

                    <thead>
                        <tr>
                            <th>Fuel Code</th>
                            <th>Name</th>
                            <th>Price/Liter</th>
                            <th>Stock (Liters)</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

                </table>

            </div>

        </div>

    </div>

    <!-- ================= ADD FUEL MODAL ================= -->
    <div id="addFuelModal" class="modal">
        <div class="modal-box large">

            <div class="modal-header">
                <h2>ADD FUEL</h2>
                <span class="close-modal" onclick="closeFuelModal()">&times;</span>
            </div>

            <form id="addFuelForm">

                <div class="modal-content">
                    <div class="form-section" style="width:100%;">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>FUEL NAME</label>
                                <input type="text" name="name" required>
                            </div>

                            <div class="input-group">
                                <label>PRICE PER LITER</label>
                                <input type="number" step="0.01" name="price_per_liter" required>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="addcancel-btn"onclick="closeFuelModal()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= EDIT FUEL MODAL ================= -->
    <div id="editFuelModal" class="modal">
        <div class="modal-box large">

            <div class="modal-header">
                <h2>EDIT FUEL</h2>
                <span class="close-modal" onclick="closeEditFuelModal()">&times;</span>
            </div>

            <form id="editFuelForm">

                <input type="hidden" name="fuel_code" id="edit_fuel_code">

                <div class="modal-content">
                    <div class="form-section" style="width:100%;">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>FUEL NAME</label>
                                <input type="text" name="name" id="edit_name" required>
                            </div>

                            <div class="input-group">
                                <label>PRICE PER LITER</label>
                                <input type="number" step="0.01" name="price_per_liter" id="edit_price" required>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">UPDATE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" onclick="closeEditFuelModal()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= SCRIPTS ================= -->

    <script>
        const fuels = <?php echo json_encode($fuels); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
    <script src="/POS-GAS/frontend/js/fuel-modal.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
   

    <!-- FUEL MODAL SCRIPT -->
    <script>
        // render table
        const tableBody = document.getElementById("tableBody");

        function renderTable() {
            tableBody.innerHTML = "";

            fuels.forEach(f => {

                tableBody.innerHTML += `
        <tr>
            <td>${f.fuel_code}</td>
            <td>${f.name}</td>
            <td>${f.price_per_liter}</td>
            <td>${f.remaining_liters} L</td>
            <td>${f.created_at}</td>
<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editFuel('${f.fuel_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteFuel('${f.fuel_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
        </tr>`;
            });
        }

        renderTable();
    </script>

</body>

</html>