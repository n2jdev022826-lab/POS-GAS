<?php



require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";




$db = new Database();
$conn = $db->connect();

$users = [];

$sql = "SELECT * FROM categories
WHERE is_deleted = 0";


$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
     <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
     <link rel="stylesheet" href="/POS-GAS/frontend/css/users.css">
</head>
<body>


    <!-- ========================================================================================================================== -->
    <!--                                                           SIDEBAR                                                          -->
    <!-- ========================================================================================================================== -->
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

                <li class="active" onclick="window.location.href='productspage';">
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

                <li >
                    <img src="/POS-GAS/frontend/assets/icons/user-icon.png" class="menu-icon">
                    <span>Users</span>
                </li>

                <li onclick="window.location.href='tracker.php';">
                    <img src="/POS-GAS/frontend/assets/icons/tracker-icon.png" class="menu-icon">
                    <span>Track Supplies</span>
                </li>

            </ul>

        </div>

        <div class="logout" onclick="window.location.href='session.php';">
            <img src="/POS-GAS/frontend/assets/icons/logout-icon.png" class="menu-icon">
            LOG OUT
        </div>

    </div>

    <!-- ========================================================================================================================== -->
    <!--                                                      MAIN CONTENT                                                          -->
    <!-- ========================================================================================================================== -->
    <div class="main">


        <!-- ========================================================================================================================== -->
        <!--                                                      TOP BAR                                                               -->
        <!-- ========================================================================================================================== -->
        <div class="topbar">
            <div id="datetime"></div>

            <div class="employee-info">
                <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
                <div id="employee-profile">
                    <img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img">
                </div>
            </div>
        </div>


        <!-- ========================================================================================================================== -->
        <!--                                          TABLE AND ACTION BUTTONS                                                          -->
        <!-- ========================================================================================================================== -->
        <div class="user-container">

            <div class="user-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search user..." />
                </div>

                <button class="btn add-btn" onclick="addCategory()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <div class="table-wrapper">

                <table id="userTable">

                    <thead>
                        <tr>
                            <th id="thusercode">Category Code</th>
                            <th id="thfullname">Category Description</th>
                            <th>Created At</th>
                            <th id="thaction">Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

                    <tfoot>
                        <tr>
                            <td colspan="13"></td>
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

    

    <!-- ================= ADD CATEGORY MODAL ================= -->
    <div id="addCategoryModal" class="modal">
        <div class="modal-box large">

            <div class="modal-header">
                <h2>ADD FUEL</h2>
                <span class="close-modal" onclick="closeCategoryModal()">&times;</span>
            </div>

            <form id="addCategoryForm">

                <div class="modal-content">
                    <div class="form-section" style="width:100%;">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>CATEGORY NAME</label>
                                <input type="text" name="category_name" required>
                            </div>

                            <div class="input-group">
                                <label>CATEGORY DESCRIPTION</label>
                                <input type="text"  name="category_description" required>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="addcancel-btn"onclick="closeCategoryModal()">Cancel</button>
                            </div>

                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>


  
</body>
</html>

  <script src="/POS-GAS/frontend/js/alert.js"></script>

<script>
    document.getElementById("addCategoryForm").addEventListener("submit", function(e){
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("http://localhost/POS-GAS/api/categories/create.php",{
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log("Server Response:", data);

            
            if(data.status === "success"){
                showAlert(
                    "success",
                    "Category Added",
                    data.message,
                    "OK",
                    function() {
                        window.location.reload();
                    }
                );
            }else{
                showAlert("error", "Failed", data.message);
            }   
           
            

        })
        .catch(err => {
            console.error("Error:", err);
            alert("An error occurred while adding the category.");
        })
    })
    
</script>

 <script src="/POS-GAS/frontend/js/category-modal.js"></script>