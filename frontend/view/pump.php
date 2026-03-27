<?php
require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

// Fetch pumps
$pumps = [];

$sql = "SELECT * FROM pumps WHERE is_deleted = 0";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $pumps[] = $row;
}

// Fetch fuels
$fuels = [];
$fuelQuery = "SELECT id, name FROM fuels WHERE is_deleted = 0";
$fuelResult = $conn->query($fuelQuery);

while ($row = $fuelResult->fetch_assoc()) {
    $fuels[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    
    <title>GAS STATION</title>
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


        <form id="addPumpForm">
            <div class="modal-grid">

                <div class="input-group">
                    <label>ENTER PUMP NUMBER</label>
                    <input type="text" name="pump_number" required>
                </div>

                <div class="input-group">
                    <label>SELECT FUEL</label>
                    <select name="fuel_type" required>
                        <option value="">-- Select Fuel --</option>

                        <?php foreach ($fuels as $fuel): ?>
                            <option value="<?= $fuel['id']; ?>">
                                <?= htmlspecialchars($fuel['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <input type="text" name="pump_code" value="PUMPCODE-00000001">

                <div class="input-group">
                    <select name="status" id="">
                        <option value="available">AVAILABLE</option>
                        <option value="not-available">NOT-AVAILABLE</option>
                    </select>
                </div>



            </div>

            <div class="modal-buttons">
                <button type="submit" class="save-btn">+ SAVE</button>
                <button type="button" class="cancel-btn" onclick="closeUserModal()">Cancel</button>
            </div>
        </form>



        <h2>Pump List</h2>

        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>Pump Code</th>
                    <th>Fuel Type</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pumps)): ?>
                    <?php foreach ($pumps as $pump): ?>
                        <tr>

                            <td><?= htmlspecialchars($pump['pump_code']) ?></td>
                            <td><?= htmlspecialchars($pump['fuel_id']) ?></td>
                            <td><?= date("F d, Y h:i A", strtotime($pump['created_at'])) ?></td>
                            <td>
                                <button class="delete-btn" data-id="<?= $pump['pump_code'] ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No pumps found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>



</body>

</html>

<script src="/POS-GAS/frontend/js/alert.js"></script>
<script src="/POS-GAS/frontend/js/dropdown.js"></script>

<script>
    document.getElementById("addPumpForm").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("http://localhost/POS-GAS/api/pump/update.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);


                if (data.status === "success") {
                    showAlert(
                        "success",
                        "Category Added",
                        data.message,
                        "OK",
                        function() {
                            window.location.reload();
                        }
                    );
                } else {
                    showAlert("error", "Failed", data.message);
                }



            })
            .catch(err => {
                console.error("Error:", err);
                alert("An error occurred while adding the category.");
            })
    })
</script>

<script>
    document.addEventListener("click", function(e) {
        if (e.target.classList.contains("delete-btn")) {

            const code = e.target.getAttribute("data-id"); // ✅ FIXED

            if (!confirm("Are you sure you want to delete this pump?")) return;

            const formData = new FormData();
            formData.append("pump_code", code);

            fetch("http://localhost/POS-GAS/api/pump/delete.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    console.log("Server Response:", data);
                    alert(data.message);

                    if (data.status === "success") {
                        location.reload();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Something went wrong!");
                });
        }
    });
</script>