<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$products = [];

$sql = "SELECT 
    p.product_code,
    p.name,
    p.category_id, 
    c.category_name AS category,
    p.supplier_id, 
    s.supplier_name AS supplier,
    p.original_price,
    p.selling_price,
    p.expiry_date,
    p.image,
    p.created_at,

    pil.quantity,
    pm.remaining_quantity AS quantity_left,

    (p.original_price * IFNULL(pil.quantity, 0)) AS total

FROM products p

LEFT JOIN categories c ON p.category_id = c.category_id
LEFT JOIN suppliers s ON p.supplier_id = s.supplier_id

-- ✅ FIXED INVENTORY JOIN
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

-- ✅ MONITORING
LEFT JOIN product_monitoring pm 
    ON p.id = pm.product_id

WHERE p.is_deleted = 0
ORDER BY p.created_at DESC;";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    // ✅ FORMAT DATE FOR INPUT TYPE="date"
    if (!empty($row['created_at'])) {
        $row['date_received'] = date('Y-m-d', strtotime($row['created_at']));
    } else {
        $row['date_received'] = '';
    }

    $products[] = $row;
}

$sql = "SELECT supplier_id, supplier_name FROM suppliers WHERE is_deleted = 0";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$sql = "SELECT category_id, category_name FROM categories WHERE is_deleted = 0";
$result = $conn->query($sql);

$categoryData = [];

while ($row = $result->fetch_assoc()) {
    $categoryData[] = $row;
}



$conn->close();



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GAS STATION</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/product.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
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

        <!-- TOP BAR -->
        <div class="topbar">
            <div id="datetime"></div>

            <div class="employee-info">
                <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
                <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
            </div>
        </div>

        <!-- PRODUCT STATS -->

        <div class="product-stats">

            <div class="stat-card">
                <span>Nearly Expire Products:</span>
                <b id="expiringCount">0</b>
            </div>

            <div class="stat-card">
                <span>Total No. of Products:</span>
                <b id="totalProducts">0</b>
            </div>

            <div class="stat-card">
                <span>Products are below QTY of 10:</span>
                <b id="lowStockCount">0</b>
            </div>

        </div>

        <div class="produt-container">

            <!-- SEARCH BAR -->

            <div class="product-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search product...">
                </div>

                <button class="btn add-btn" onclick="addProduct()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>
                <button></button>

            </div>

            <!-- TABLE -->

            <div class="table-wrapper">

                <table id="salesTable">

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
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody id="tableBody">

                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="12"></td>
                            <td></td>
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

    <!-- ========================================================================================================================== -->
    <!--                                          ADD PRODUCTS MODAL FORM                                                               -->
    <!-- ========================================================================================================================== -->

    <div id="addProductModal" class="modal">

        <div class="modal-box large">

            <div class="modal-header">
                <h2>ADD PRODUCT</h2>
                <span class="close-modal" onclick="closeProductModal()">&times;</span>
            </div>

            <form id="addProductForm" enctype="multipart/form-data">

                <div class="modal-content">

                    <!-- LEFT SIDE -->
                    <div class="image-section">

                        <label id="imageLabel">PRODUCT IMAGE</label>

                        <div class="image-preview" id="productImagePreview"></div>

                        <input type="file" name="image" id="productImageInput" hidden>

                        <button type="button" class="upload-btn"
                            onclick="document.getElementById('productImageInput').click()">
                            UPLOAD
                        </button>

                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="form-section">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>PRODUCT NAME</label>
                                <input type="text" name="name" required>
                            </div>

                            <div class="input-group">
                                <label>CATEGORY</label>
                                <select name="category_id">
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>SUPPLIER</label>
                                <select name="supplier_id">
                                    <option value="">Select Supplier</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>DATE RECEIVED</label>
                                <input type="date" name="date_received">
                            </div>

                            <div class="input-group">
                                <label>EXPIRY DATE</label>
                                <input type="date" name="expiry_date">
                            </div>

                            <div class="input-group">
                                <label>ORIGINAL PRICE</label>
                                <input type="number" name="original_price" step="0.01">
                            </div>

                            <div class="input-group">
                                <label>SELLING PRICE</label>
                                <input type="number" name="selling_price" step="0.01">
                            </div>

                            <div class="input-group">
                                <label>QTY.</label>
                                <input type="number" name="quantity">
                            </div>

                            <div class="input-group">
                                <label>QTY. LEFT</label>
                                <input type="number" name="quantity_left" readonly>
                            </div>

                            <div class="input-group">
                                <label>TOTAL</label>
                                <input type="number" name="total" readonly>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="addcancel-btn" onclick="closeProductModal()">Cancel</button>
                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <!-- ========================================================================================================================== -->
    <!--                                          EDIT PRODUCT MODAL FORM                                                               -->
    <!-- ========================================================================================================================== -->

    <div id="editProductModal" class="modal">

        <div class="modal-box large">

            <div class="modal-header">
                <h2>EDIT PRODUCT</h2>
                <span class="close-modal" onclick="closeEditProductModal()">&times;</span>
            </div>

            <form id="editProductForm" enctype="multipart/form-data">

                <input type="hidden" name="product_code" id="edit_product_code">

                <div class="modal-content">

                    <!-- LEFT -->
                    <div class="image-section">

                        <label id="imageLabel">PRODUCT IMAGE</label>

                        <div class="image-preview" id="editProductImagePreview"></div>

                        <input type="file" name="image" id="editProductImageInput" hidden>

                        <button type="button" class="upload-btn"
                            onclick="document.getElementById('editProductImageInput').click()">
                            UPLOAD
                        </button>

                    </div>

                    <!-- RIGHT -->
                    <div class="form-section">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>PRODUCT NAME</label>
                                <input type="text" name="name" id="edit_name">
                            </div>

                            <div class="input-group">
                                <label>CATEGORY</label>
                                <select name="category_id" id="edit_category"></select>
                            </div>

                            <div class="input-group">
                                <label>SUPPLIER</label>
                                <select name="supplier_id" id="edit_supplier"></select>
                            </div>

                            <div class="input-group">
                                <label>DATE RECEIVED</label>
                                <input type="date" name="date_received" id="edit_date_received">
                            </div>

                            <div class="input-group">
                                <label>EXPIRY DATE</label>
                                <input type="date" name="expiry_date" id="edit_expiry_date">
                            </div>

                            <div class="input-group">
                                <label>ORIGINAL PRICE</label>
                                <input type="number" name="original_price" id="edit_original_price">
                            </div>

                            <div class="input-group">
                                <label>SELLING PRICE</label>
                                <input type="number" name="selling_price" id="edit_selling_price">
                            </div>

                            <div class="input-group">
                                <label>QTY.</label>
                                <input type="number" name="quantity" id="edit_quantity">
                            </div>

                            <div class="input-group">
                                <label>QTY. LEFT</label>
                                <input type="number" name="quantity_left" id="edit_quantity_left" readonly>
                            </div>

                            <div class="input-group">
                                <label>TOTAL</label>
                                <input type="number" name="total" id="edit_total" readonly>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">UPDATE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="editcancel-btn" onclick="closeEditProductModal()">Cancel</button>
                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>


    <script>
        let supplierList = <?php echo json_encode($data); ?>;
        let categoryList = <?php echo json_encode($categoryData); ?>;
        const products = <?php echo json_encode($products); ?>;
    </script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/products-modal.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
    <script src="/POS-GAS/frontend/js/products-page.js"></script>
    <script src="/POS-GAS/frontend/js/load.js"></script>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>



</body>

</html>