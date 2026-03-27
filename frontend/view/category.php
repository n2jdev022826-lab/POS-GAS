<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$categories = [];

$sql = "SELECT category_code, category_name, description, created_at 
        FROM categories WHERE is_deleted = 0";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAS STATION</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/supplier.css"> <!-- reuse -->
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">

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

                <li class="active" onclick="window.location.href='category';">
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

        <!-- ================= TOPBAR ================= -->
        <div class="topbar">
            <div id="datetime"></div>

            <div class="employee-info" id="employeeMenu">
                <div class="employee-name">
                    <?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?>
                </div>

                <div id="employee-profile">
                    <img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img">
                </div>

                <div class="employee-dropdown" id="employeeDropdown">
                    <div class="dropdown-item" onclick="goToAccount()">Account Settings</div>
                    <div class="dropdown-item" onclick="logout()">Logout</div>
                </div>
            </div>
        </div>

        <!-- ================= CONTENT ================= -->
        <div class="supplier-container">

            <!-- CONTROLS -->
            <div class="supplier-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search category..." />
                </div>

                <button class="btn add-btn" onclick="addCategory()">+ Add</button>

                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <!-- TABLE -->
            <div class="table-wrapper">
                <table id="categoryTable">

                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

                    <tfoot>
                        <tr>
                            <td colspan="5"></td>
                        </tr>
                    </tfoot>

                </table>
            </div>

            <!-- PAGINATION -->
            <div class="pagination-container">

                <div class="limit-box">
                    Show
                    <select id="rowsPerPage">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
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

    <!-- ================= ADD CATEGORY MODAL ================= -->
    <div id="addCategoryModal" class="modal">
        <div class="modal-box">
            <div class="modal-header">
                <h2>ADD CATEGORY</h2>
                <span class="close-modal" onclick="closeCategoryModal()">&times;</span>
            </div>

            <form id="addCategoryForm">
                <div class="modal-content">
                    <div class="form-grid">

                        <div class="input-group">
                            <label>CATEGORY NAME</label>
                            <input type="text" name="category_name" required>
                        </div>

                        <div class="input-group">
                            <label>DESCRIPTION</label>
                            <input type="text" name="category_description" required>
                        </div>

                        <div class="modal-buttons">
                            <button type="submit" class="save-btn">+ SAVE</button>
                        </div>

                        <div class="modal-buttons">
                            <button type="button" class="editcancel-btn" onclick="closeCategoryModal()">Cancel</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= EDIT CATEGORY MODAL ================= -->
    <div id="editCategoryModal" class="modal">
        <div class="modal-box">
            <div class="modal-header">
                <h2>EDIT CATEGORY</h2>
                <span class="close-modal" onclick="closeEditCategoryModal()">&times;</span>
            </div>

            <form id="editCategoryForm">
                <input type="hidden" name="category_code" id="edit_category_code">

                <div class="modal-content">
                    <div class="form-grid">

                        <div class="input-group">
                            <label>CATEGORY NAME</label>
                            <input type="text" name="category_name" id="edit_category_name" required>
                        </div>

                        <div class="input-group">
                            <label>DESCRIPTION</label>
                            <input type="text" name="category_description" id="edit_category_description" required>
                        </div>

                        <div class="modal-buttons">
                            <button type="submit" class="save-btn">UPDATE</button>
                        </div>

                        <div class="modal-buttons">
                            <button type="button" class="editcancel-btn" onclick="closeEditCategoryModal()">Cancel</button>
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= GLOBAL DATA ================= -->
    <script>
        const categories = <?php echo json_encode($categories); ?>;
    </script>

    <!-- ================= JS FILES ================= -->
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/category-modal.js"></script>
    <script src="/POS-GAS/frontend/js/category-page.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
    <script src="/POS-GAS/frontend/js/dropdown.js"></script>
</body>

</html>