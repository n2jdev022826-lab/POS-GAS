<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$products = [];

$sql = "SELECT *
FROM Products
WHERE is_deleted = 0";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}


$sql = "SELECT supplier_name FROM suppliers WHERE is_deleted = 0";
$result = $conn->query($sql);

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

$sql = "SELECT category_id, category_name FROM categories WHERE is_deleted = 0";
$result = $conn->query($sql);

$categoryData = [];

while($row = $result->fetch_assoc()){
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
    <style>
    /* container */
    .input-group{
        position: relative;
    }

    /* search input */
    #searchBox{
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    #categorySearch{
        width: 100%;
        padding: 8px 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        font-size: 14px;
    }

    /* dropdown container */
    .dropdown-list{
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        max-height: 180px;
        overflow-y: auto;

        background: white;
        border: 1px solid #ccc;
        border-top: none;

        display: none;
        z-index: 1000;

        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* dropdown item */
    .dropdown-item{
        padding: 8px 10px;
        cursor: pointer;
        font-size: 14px;
    }

    /* hover effect */
    .dropdown-item:hover{
        background: #f5f5f5;
    }

    /* scrollbar styling */
    .dropdown-list::-webkit-scrollbar{
        width: 6px;
    }

    .dropdown-list::-webkit-scrollbar-thumb{
        background: #ccc;
        border-radius: 3px;
    }

    .dropdown-list::-webkit-scrollbar-thumb:hover{
        background: #999;
    }
    </style>
    <link rel="stylesheet" href="/POS-GAS/frontend/css/product.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">

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
                            <th>Product Code</th>
                            <th>Product Name</th>
                            <th>Generic Name</th>
                            <th>Category</th>
                            <th>Supplier</th>
                            <th>Date Received</th>
                            <th>Expiry Date</th>
                            <th>Original Price</th>
                            <th>Selling Price</th>
                            <th>QTY.</th>
                            <th>QTY. Left</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody id="tableBody"></tbody>

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

    <!-- ================= ADD PRODUCT MODAL ================= -->



    <div id="addProductModal" class="modal">

        <div class="modal-box">

            <div class="modal-header">
                <h2>Add Product</h2>
                <span class="close-modal" onclick="closeProductModal()">&times;</span>
            </div>

            <form id="addProductForm">

                <div class="modal-grid">

                    <div class="input-group">
                        <label>Product Name</label>
                        <input type="text"  name="product_name" required>

                       
                    </div>

                    <div class="input-group">
                        <label>Generic Name</label>
                        <input type="text" name="generic_name">
                    </div>

                    <div class="input-group">
    <label>Category</label>
    <input type="text" id="categorySearch" name="category" required autocomplete="off">
    
    <div id="categoryDropdown" class="dropdown-list"></div>

    <input type="hidden" id="selectedCategoryID">
</div>

                    <div class="input-group">
                        <label>Supplier</label>
                        <input type="text" id="searchBox" name="supplier" required autocomplete="off">
                         <div id="dropdownList" class="dropdown-list"></div> 

                        <input type="hidden" id="selectedID">
                    </div>

                    <div class="input-group">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date">
                    </div>

                    <div class="input-group">
                        <label>Original Price</label>
                        <input type="number" step="0.01" name="purchase_price">
                    </div>

                    <div class="input-group">
                        <label>Selling Price</label>
                        <input type="number" step="0.01" name="selling_price">
                    </div>

                    <div class="input-group">
                        <label>Quantity</label>
                        <input type="number" name="stock_quantity">
                    </div>

                    <input type="hidden" name="created_by" value="<?php echo $_SESSION['user_id']; ?>">

                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeProductModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save Product</button>
                </div>

            </form>

        </div>

    </div>

    <script>
        const products = <?php echo json_encode($products); ?>;

        function displayProducts() {

            const body = document.getElementById("tableBody");
            body.innerHTML = "";

            products.forEach(p => {

                const total = p.selling_price * p.stock_quantity;

                body.innerHTML += `

<tr>

<td>${p.product_code}</td>
<td>${p.product_name}</td>
<td>${p.generic_name}</td>
<td>${p.category}</td>
<td>${p.supplier ?? ''}</td>
<td>-</td>
<td>${p.expiry_date ?? ''}</td>
<td>${p.purchase_price}</td>
<td>${p.selling_price}</td>
<td>${p.stock_quantity}</td>
<td>${p.stock_quantity}</td>
<td>${total}</td>

<td>

<button onclick='editProduct(${JSON.stringify(p)})'>Edit</button>

<form action="../../backend/products/delete_product.php" method="POST" style="display:inline">

<input type="hidden" name="product_id" value="${p.product_id}">
<input type="hidden" name="deleted_by" value="<?php echo $_SESSION['user_id']; ?>">

<button type="submit">Delete</button>

</form>

</td>

</tr>

`;

            });

            document.getElementById("totalProducts").innerText = products.length;

            const low = products.filter(p => p.stock_quantity <= 10);
            document.getElementById("lowStockCount").innerText = low.length;

            const today = new Date();

            const expiring = products.filter(p => {

                if (!p.expiry_date) return false;

                const exp = new Date(p.expiry_date);
                const diff = (exp - today) / (1000 * 60 * 60 * 24);

                return diff <= 30;

            });

            document.getElementById("expiringCount").innerText = expiring.length;

        }

        displayProducts();
    </script>

    <script>
        let dataList = <?php echo json_encode($data); ?>;
        let categoryList = <?php echo json_encode($categoryData); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/filtersupplier.js"></script>
    <script src="/POS-GAS/frontend/js/filtercategory.js"></script>
    <script src="/POS-GAS/frontend/js/product-modal.js"></script>
    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>


</body>

</html>