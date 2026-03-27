<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$customers = [];

$sql = "SELECT * FROM customers WHERE is_deleted = 0";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $row['created_at'] = date("F d, Y h:i A", strtotime($row['created_at']));
    $customers[] = $row;
}



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAS STATION</title>
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/customer.css">
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

                <li class="active" onclick="window.location.href='customer';">
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

        <!-- ================= CONTENTS ================= -->
        <div class="customer-container">

            <div class="customer-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search customer...">
                </div>

                <button class="btn add-btn" onclick="openModal()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <div class="table-wrapper">

                <table id="customerTable">

                    <thead>
                        <tr>
                            <th>Customer Code</th>
                            <th>Full Name</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Email</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7"></td>
                        </tr>
                    </tfoot>


                </table>

            </div>

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

    <!-- ================= MODAL ================= -->

    <div class="modal" id="customerModal">

        <div class="modal-box">

            <div class="modal-header">
                <h2>Add Customer</h2>
                <span class="close-btn" onclick="closeModal()">×</span>
            </div>

            <form id="addCustomer">
                <div class="modal-form">

                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="customer_name" placeholder="Customer Name" required>
                    </div>

                    <div class="input-group">
                        <label>Contact No.</label>
                        <input type="text" name="phone" placeholder="Phone Number" required
                            pattern="09[0-9]{9}" title="Enter a valid PH mobile number e.g. 09756657044"
                            maxlength="11">
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" placeholder="Email" required>
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <textarea name="address" id="" placeholder="Address" required></textarea>
                    </div>


                </div>
                <div class="modal-buttons">
                    <button class="btn save-btn" type="submit">Save Customer</button>
                    <button class="btn addcancel-btn" onclick="closeModal()">Cancel</button>
                </div>
            </form>



        </div>

    </div>


    <div class="modal" id="editCustomerModal">

        <div class="modal-box">

            <div class="modal-header">
                <h2>Edit Customer</h2>
                <span class="close-btn" onclick="closeEditModal()">×</span>
            </div>

            <form id="editCustomerForm">

                <input type="hidden" name="customer_code" id="edit_customer_code">

                <div class="modal-form">

                    <div class="input-group">
                        <label>Full Name</label>
                        <input type="text" name="customer_name" id="edit_customer_name" required>
                    </div>

                    <div class="input-group">
                        <label>Contact No.</label>
                        <input type="text" name="phone" placeholder="Phone Number" id="edit_phone" required
                            pattern="09[0-9]{9}" title="Enter a valid PH mobile number e.g. 09756657044"
                            maxlength="11">
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" name="email" id="edit_email" required>
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <textarea name="address" id="edit_address" required></textarea>
                    </div>

                </div>

                <div class="modal-buttons">

                    <button type="submit" class="btn save-btn">Update Customer</button>
                    <button type="button" class="btn editcancel-btn" onclick="closeEditModal()">Cancel</button>
                </div>

            </form>

        </div>

    </div>


    <script>
        const customers = <?php echo json_encode($customers); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
    <script src="/POS-GAS/frontend/js/customer-modal.js"></script>
    <script src="/POS-GAS/frontend/js/customer-page.js"></script>
    <script src="/POS-GAS/frontend/js/dropdown.js"></script>

</body>

</html>