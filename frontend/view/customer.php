<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/auth.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAYLO MEDICAL CLINIC</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/customer.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">

</head>

<body>

    <!-- ================= SIDEBAR ================= -->

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

                <li onclick="window.location.href='products';">
                    <img src="/POS-GAS/frontend/assets/icons/products-icon.png" class="menu-icon">
                    <span>Products</span>
                </li>

                <li class="active">
                    <img src="/POS-GAS/frontend/assets/icons/customer-icon.png" class="menu-icon">
                    <span>Customers</span>
                </li>

                <li onclick="window.location.href='supplier';">
                    <img src="/POS-GAS/frontend/assets/icons/supplier-icon.png" class="menu-icon">
                    <span>Suppliers</span>
                </li>

                <li onclick="window.location.href='report';">
                    <img src="/POS-GAS/frontend/assets/icons/report-icon.png" class="menu-icon">
                    <span>Report</span>
                </li>

                <li onclick="window.location.href='debt';">
                    <img src="/POS-GAS/frontend/assets/icons/debt-icon.png" class="menu-icon">
                    <span>Manage Debts</span>
                </li>

                <li onclick="window.location.href='users';">
                    <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
                    <span>Users</span>
                </li>

                <li onclick="window.location.href='tracker';">
                    <img src="/POS-GAS/frontend/assets/icons/tracker-icon.png" class="menu-icon">
                    <span>Track Supplies</span>
                </li>

            </ul>
        </div>

        <div class="logout" onclick="window.location.href='index.php';">
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
                <div id="employee-profile">
                <img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
            </div>
        </div>

        </div>

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
                            <th>Fullname</th>
                            <th>Address</th>
                            <th>Contact No.</th>
                            <th>Product Name</th>
                            <th>Total Amount</th>
                            <th>Note</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

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

            <div class="modal-form">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" id="fullname" placeholder="Enter full name">
                </div>

                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" id="contact" placeholder="09XXXXXXXXX">
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <input type="text" id="address" placeholder="Customer address">
                </div>

                <div class="form-group">
                    <label>Product Name</label>
                    <input type="text" id="product" placeholder="Medicine bought">
                </div>

                <div class="form-group">
                    <label>Total Amount</label>
                    <input type="number" id="amount" placeholder="0.00">
                </div>

                <div class="form-group">
                    <label>Due Date</label>
                    <input type="date" id="duedate">
                </div>

                <div class="form-group full">
                    <label>Note</label>
                    <input type="text" id="note" placeholder="Optional note">
                </div>

            </div>

            <div class="modal-buttons">
                <button class="btn cancel-btn" onclick="closeModal()">Cancel</button>
                <button class="btn save-btn" onclick="saveCustomer()">Save Customer</button>
            </div>

        </div>

    </div>

    <script src="/POS-GAS/frontend/js/customer.js"></script>
    <script src="/POS-GAS/frontend/js/customer-modal.js"></script>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>

</body>

</html>