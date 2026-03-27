<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

// FETCH PUMPS
$pumps = [];

$sql = "SELECT 
p.pump_code,
p.pump_name,
f.name AS fuel_name,
p.fuel_id,
p.status,
p.created_at
FROM pumps p
LEFT JOIN fuels f ON p.fuel_id = f.id
WHERE p.is_deleted = 0";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $pumps[] = $row;
}

// FETCH FUELS
$fuels = [];
$fuelQuery = "SELECT id, name FROM fuels WHERE is_deleted = 0";
$fuelResult = $conn->query($fuelQuery);

while ($row = $fuelResult->fetch_assoc()) {
    $fuels[] = $row;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>GAS STATION</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/pump.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
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

        <li class="active" onclick="window.location.href='pump';">
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

        <div class="pump-container">

            <div class="pump-controls">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search pump..." />
                </div>
                <button class="btn add-btn" onclick="addPump()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
            </div>

            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>Pump Code</th>
                            <th>Pump Number</th>
                            <th>Fuel</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6"></td>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <div class="pagination-container">
                <div class="limit-box">
                    Show
                    <select id="rowsPerPage">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                    </select>
                    entries
                </div>

                <div class="pagination">
                    <button>Prev</button>
                    <span id="pageInfo">Page 1</span>
                    <button>Next</button>
                </div>

            </div>
        </div>
    </div>



    <!-- ADD MODAL -->
    <div id="addPumpModal" class="modal">
        <div class="modal-box large">
            <div class="modal-header">
                <h2>ADD PUMP</h2>
                <span class="close-modal" onclick="closePumpModal()">&times;</span>
            </div>

            <form id="addPumpForm">
                <div class="modal-content">
                    <div class="form-section" style="width:100%;">
                        <div class="form-grid">

                            <div class="input-group">
                                <label>PUMP NUMBER</label>
                                <input type="text" name="pump_number" required>
                            </div>

                            <div class="input-group">
                                <label>FUEL</label>
                                <select name="fuel_id" required>
                                    <option value="">-- Select Fuel --</option>
                                    <?php foreach ($fuels as $f): ?>
                                        <option value="<?= $f['id']; ?>"><?= $f['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>STATUS</label>
                                <select name="status">
                                    <option value="available">AVAILABLE</option>
                                    <option value="not-available">NOT AVAILABLE</option>
                                </select>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="editcancel-btn" onclick="closePumpModal()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div id="editPumpModal" class="modal">
        <div class="modal-box large">
            <div class="modal-header">
                <h2>EDIT PUMP</h2>
                <span class="close-modal" onclick="closeEditPumpModal()">&times;</span>
            </div>

            <form id="editPumpForm">

                <input type="hidden" name="pump_code" id="edit_pump_code">

                <div class="modal-content">
                    <div class="form-section" style="width:100%;">
                        <div class="form-grid">

                            <div class="input-group">
                                <label>PUMP NUMBER</label>
                                <input type="text" name="pump_number" id="edit_pump_number">
                            </div>

                            <div class="input-group">
                                <label>FUEL</label>
                                <select name="fuel_id" id="edit_fuel_id">
                                    <?php foreach ($fuels as $f): ?>
                                        <option value="<?= $f['id']; ?>"><?= $f['name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>STATUS</label>
                                <select name="status" id="edit_status">
                                    <option value="available">AVAILABLE</option>
                                    <option value="not-available">NOT AVAILABLE</option>
                                </select>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">UPDATE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="editcancel-btn" onclick="closeEditPumpModal()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>


    <script>
        const pumps = <?php echo json_encode($pumps); ?>;
        const fuels = <?php echo json_encode($fuels); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/pump-modal.js"></script>
    <script src="/POS-GAS/frontend/js/pump-page.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
    <script src="/POS-GAS/frontend/js/dropdown.js"></script>
     <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>

</body>

</html>