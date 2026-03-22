<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$suppliers = [];

$sql = "SELECT
supplier_code,
supplier_name,
contact_name,
phone,
email,
address
FROM suppliers";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAYLO MEDICAL CLINIC</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/supplier.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">

</head>

<body>

    <!-- SIDEBAR -->

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

                <li class="active">
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

    <!-- MAIN -->

    <div class="main">

     <!-- TOP BAR -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
        </div>
      </div>


        <div class="supplier-container">

            <div class="supplier-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search supplier..." />
                </div>

                <button class="btn add-btn" onclick="addSupplier()">+ Add</button>

                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <div class="table-wrapper">

                <table id="supplierTable">

                    <thead>
                        <tr>
                            <th>Supplier Code</th>
                            <th>Supplier</th>
                            <th>Contact Person</th>
                            <th>Contact No.</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

                    <tfoot>
                        <tr>
                            <td colspan="8"></td>
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
                        <option value="20">20</option>
                        <option value="50">50</option>
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

    <!-- ADD SUPPLIER MODAL -->

    <div class="modal" id="addSupplierModal">

        <div class="modal-box">

            <div class="modal-header">
                <h2>Add Supplier</h2>
                <span class="close-btn" onclick="closeSupplierModal()">×</span>
            </div>

            <form id="addSupplierForm">

                <div class="modal-form">

                    <div class="form-group">
                        <label>Supplier Name</label>
                        <input type="text" name="supplier_name" required>
                    </div>

                    <div class="form-group">
                        <label>Contact Person</label>
                        <input type="text" name="contact_name">
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address">
                    </div>

                    <div class="form-group">
                        <label>Contact No.</label>
                        <input type="text" name="phone">
                    </div>

                    <div class="form-group full">
                        <label>Email</label>
                        <input type="email" name="email">
                    </div>

                </div>

                <div class="modal-buttons">

                    <button type="button" class="btn cancel-btn" onclick="closeSupplierModal()">Cancel</button>

                    <button type="submit" class="btn save-btn">Save Supplier</button>

                </div>

            </form>

        </div>

    </div>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/supplier-modal.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>

    <script>
        const suppliers = <?php echo json_encode($suppliers); ?>;

        function displaySuppliers() {

            const tableBody = document.getElementById("tableBody");

            tableBody.innerHTML = "";

            const filteredSuppliers = suppliers.filter(supplier => {

                return (
                    (supplier.supplier_name && supplier.supplier_name.toLowerCase().includes(searchQuery)) ||
                    (supplier.contact_name && supplier.contact_name.toLowerCase().includes(searchQuery)) ||
                    (supplier.phone && supplier.phone.toLowerCase().includes(searchQuery))
                );

            });

            filteredSuppliers.forEach(supplier => {

                const row = `
<tr>

<td>${supplier.supplier_code}</td>
<td>${supplier.supplier_name}</td>
<td>${supplier.contact_name}</td>
<td>${supplier.phone}</td>
<td>${supplier.email}</td>
<td>${supplier.address}</td>

  <td class="action-buttons">
        <button class="icon-btn edit-btn"
        onclick="editUser('${supplier.user_code}')">
            <img src="/POS-GAS/frontend/assets/icons/edit.png">
            <span>EDIT</span>
        </button>

        <button class="icon-btn delete-btn"
        onclick="deleteUser('${supplier.user_code}')">
            <img src="/POS-GAS/frontend/assets/icons/delete.png">
            <span>DELETE</span>
        </button>
    </td>

</tr>
`;

                tableBody.innerHTML += row;

            });

        }

        displaySuppliers();
    </script>

</body>

</html>