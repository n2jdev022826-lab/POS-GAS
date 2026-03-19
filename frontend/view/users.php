<?php



require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";




$db = new Database();
$conn = $db->connect();

$users = [];

$sql = "SELECT 
user_code,
fname,
middlename,
lname,
username,
position,
phone,
email,
address,
hire_date,
created_at
FROM users";

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
    <title>JAYLO MEDICAL CLINIC</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/users.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/print.css">

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

                <li class="active">
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

    <!-- MAIN -->
    <div class="main">

        <div class="topbar">
            <div id="datetime"></div>

            <div class="employee-info">
                <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname']. ", " . $_SESSION['fname']); ?></div>
                <div id="employee-profile"></div>
            </div>
        </div>

        <div class="user-container">

            <div class="user-controls">

                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="Search user..." />
                </div>

                <button class="btn add-btn" onclick="addUsers()">+ Add</button>
                <button class="btn print-btn" onclick="printReceipt()">🖨 Print</button>

            </div>

            <div class="table-wrapper">

                <table id="userTable">

                    <thead>
                        <tr>
                            <th>User Code</th>
                            <th>Full Name</th>
                            <th>Username</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Address</th>
                            <th>Date Hired</th>
                            <th>Created At</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody id="tableBody"></tbody>

                    <tfoot>
                        <tr>
                            <td colspan="10"></td>
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

    <!-- ADD USER MODAL -->

    <div id="addUserModal" class="modal">

        <div class="modal-box">

            <div class="modal-header">
                <h2>Add User</h2>
                <span class="close-modal" onclick="closeUserModal()">&times;</span>
            </div>

            <form id="addUserForm">

                <div class="modal-grid">

                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" required name="firstname">
                    </div>

                    <div class="input-group">
                        <label>Middle Name</label>
                        <input type="text" name="middlename">
                    </div>

                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" required name="lastname">
                    </div>

                    <div class="input-group">
                        <label>Username</label>
                        <input type="text" required name="username">
                    </div>

                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" required name="email">
                    </div>

                    <div class="input-group">
                        <label>Role</label>
                        <select name="role" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="staff">Staff</option>
                            <option value="cashier">Cashier</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Contact No.</label>
                        <input type="text" required name="phone">
                    </div>

                    <div class="input-group">
                        <label>Date Hired</label>
                        <input type="date" name="hire_date">
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <input type="text" name="address" required>
                    </div>

                    <div class="input-group">
                        <label>Birth Date/label>
                        <input type="date" name="birthdate" required>
                    </div>

                    <div class="input-group">
                        <label>Password</label>
                        <input type="password" required name="password">
                    </div>

                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeUserModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save User</button>
                </div>

            </form>

        </div>
    </div>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/users-modal.js"></script>
    

    <script>
        const users = <?php echo json_encode($users); ?>;

        // DISPLAY USERS
        function displayUsers() {

            const tableBody = document.getElementById("tableBody");

            tableBody.innerHTML = "";

            // FILTER USERS
            const filteredUsers = users.filter(user => {

                return (
                    (user.user_code && user.user_code.toLowerCase().includes(searchQuery)) ||
                    (user.fname && user.fname.toLowerCase().includes(searchQuery)) ||
                    (user.lname && user.lname.toLowerCase().includes(searchQuery)) ||
                    (user.username && user.username.toLowerCase().includes(searchQuery)) ||
                    (user.position && user.position.toLowerCase().includes(searchQuery)) ||
                    (user.email && user.email.toLowerCase().includes(searchQuery))
                );

            });

            // DISPLAY FILTERED USERS
            filteredUsers.forEach(user => {

                const row = `
        <tr>
            <td>${user.user_code}</td>
            <td>${user.fname} ${user.middlename} ${user.lname}</td>
            <td>${user.username}</td>
            <td>${user.position}</td>
            <td>${user.phone}</td>
            <td>${user.email}</td>
            <td>${user.address}</td>
            <td>${user.hire_date}</td>
            <td>${user.created_at}</td>

            <td class="action-buttons">

                <button class="icon-btn edit-btn"
                onclick="editUser('${user.user_code}')">

                    <img src="/POS-GAS/frontend/assets/icons/edit.png" alt="Edit">
                    <span>EDIT</span>
                </button>

                <button class="icon-btn delete-btn"
                onclick="deleteUser('${user.user_code}')">

                    <img src="/POS-GAS/frontend/assets/icons/delete.png" alt="Delete">
                    <span>DELETE</span>
                </button>

            </td>

        </tr>
        `;

                tableBody.innerHTML += row;

            });

        }

        // INITIAL LOAD
        displayUsers();


    </script>
</body>

</html>