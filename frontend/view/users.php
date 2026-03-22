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
    sex,
    username,
    role,
    address,
    phone,
    email,
    date_of_birth,
    hire_date,
    image,
    created_at
FROM users
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
    <title>GAS STATION</title>

    <link rel="stylesheet" href="/POS-GAS/frontend/css/global.css">
    <link rel="stylesheet" href="/POS-GAS/frontend/css/users.css">
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
                <div class="employee-name"> <?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
                <div id="employee-profile">
                    <img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img">
                </div>
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
                            <th>Profile</th>
                            <th id="thusercode">User Code</th>
                            <th id="thfullname">Full Name</th>
                            <th id="thsex">Sex</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Address</th>
                            <th id="tdphone">Phone</th>
                            <th>Email</th>
                            <th>Birthdate</th>
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

    <!-- ADD USER MODAL -->

    <div id="addUserModal" class="modal">

        <div class="modal-box large">

            <div class="modal-header">
                <h2>ADD USER</h2>
                <span class="close-modal" onclick="closeUserModal()">&times;</span>
            </div>

            <form id="addUserForm" enctype="multipart/form-data">

                <div class="modal-content">

                    <!-- LEFT SIDE (IMAGE) -->
                    <div class="image-section">

                        <label id="imageLabel">USER IMAGE</label>

                        <div class="image-preview" id="imagePreview"></div>

                        <input type="file" name="image" id="imageInput" hidden>

                        <button type="button" class="upload-btn" onclick="document.getElementById('imageInput').click()">
                            UPLOAD
                        </button>

                    </div>

                    <!-- RIGHT SIDE (FORM) -->
                    <div class="form-section">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>FIRST NAME</label>
                                <input type="text" name="firstname" required>
                            </div>

                            <div class="input-group">
                                <label>MIDDLE NAME</label>
                                <input type="text" name="middlename">
                            </div>

                            <div class="input-group">
                                <label>SURNAME</label>
                                <input type="text" name="lastname" required>
                            </div>

                            <div class="input-group">
                                <label>ROLE</label>
                                <select name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Cashier">Cashier</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>CONTACT NO.</label>
                                <input type="text" name="phone" required>
                            </div>

                            <div class="input-group">
                                <label>SEX</label>
                                <select name="sex" required>
                                    <option value="">Select</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>ADDRESS</label>
                                <input type="text" name="address" required>
                            </div>

                            <div class="input-group">
                                <label>EMAIL</label>
                                <input type="email" name="email" required>
                            </div>

                            <div class="input-group">
                                <label>USERNAME</label>
                                <input type="text" name="username" required>
                            </div>

                            <div class="input-group">
                                <label>PASSWORD</label>
                                <input type="password" name="password" required>
                            </div>
                            <div class="input-group">
                                <label>BIRTH DATE</label>
                                <input type="date" name="birthdate" required>
                            </div>
                            <div class="input-group">
                                <label>HIRE DATE</label>
                                <input type="date" name="hire_date">
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>


                            <div class="modal-buttons">
                                <button type="submit" class="addcancel-btn" onclick="closeUserModal()">Cancel</button>
                            </div>

                        </div>

                    </div>

                </div>

            </form>

        </div>

    </div>

    <!-- EDIT USER MODAL -->
    <div id="editUserModal" class="modal">

        <div class="modal-box large">

            <div class="modal-header">
                <h2>EDIT USER</h2>
                <span class="close-modal" onclick="closeEditModal()">&times;</span>
            </div>

            <form id="editUserForm" enctype="multipart/form-data">

                <input type="hidden" name="user_code" id="edit_user_code">

                <div class="modal-content">

                    <!-- LEFT SIDE -->
                    <div class="image-section">

                        <label id="imageLabel">USER IMAGE</label>

                        <div class="image-preview" id="editImagePreview"></div>

                        <input type="file" name="image" id="editImageInput" hidden>

                        <button type="button" class="upload-btn"
                            onclick="document.getElementById('editImageInput').click()">
                            UPLOAD
                        </button>

                    </div>

                    <!-- RIGHT SIDE -->
                    <div class="form-section">

                        <div class="form-grid">

                            <div class="input-group">
                                <label>FIRST NAME</label>
                                <input type="text" name="firstname" id="edit_firstname" required>
                            </div>

                            <div class="input-group">
                                <label>MIDDLE NAME</label>
                                <input type="text" name="middlename" id="edit_middlename">
                            </div>

                            <div class="input-group">
                                <label>SURNAME</label>
                                <input type="text" name="lastname" id="edit_lastname" required>
                            </div>

                            <div class="input-group">
                                <label>ROLE</label>
                                <select name="role" id="edit_role" required>
                                    <option value="Admin">Admin</option>
                                    <option value="Staff">Staff</option>
                                    <option value="Cashier">Cashier</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>CONTACT NO.</label>
                                <input type="text" name="phone" id="edit_phone" required>
                            </div>

                            <div class="input-group">
                                <label>SEX</label>
                                <select name="sex" id="edit_sex" required>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>

                            <div class="input-group">
                                <label>ADDRESS</label>
                                <input type="text" name="address" id="edit_address" required>
                            </div>

                            <div class="input-group">
                                <label>EMAIL</label>
                                <input type="email" name="email" id="edit_email" required>
                            </div>

                            <div class="input-group">
                                <label>USERNAME</label>
                                <input type="text" name="username" id="edit_username" required>
                            </div>

                            <div class="input-group">
                                <label>NEW PASSWORD</label>
                                <input type="password" name="password" placeholder="Leave blank to keep current password">
                            </div>

                            <div class="input-group">
                                <label>BIRTH DATE</label>
                                <input type="date" name="birthdate" id="edit_birth_date">
                            </div>

                            <div class="input-group">
                                <label>HIRE DATE</label>
                                <input type="date" name="hire_date" id="edit_hire_date">
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">UPDATE</button>
                            </div>

                            <div class="modal-buttons">
                                <button type="button" class="editcancel-btn" onclick="closeEditModal()">Cancel</button>
                            </div>
                        </div>



                    </div>

                </div>

            </form>

        </div>

    </div>

    <script>
        window.CURRENT_USER_CODE = <?php echo json_encode($_SESSION['user_code'] ?? null); ?>;
    </script>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/search.js"></script>
    <script src="/POS-GAS/frontend/js/users-modal.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>


<script>
const users = <?php echo json_encode($users); ?>;

// PAGINATION STATE
let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// DISPLAY USERS
function displayUsers() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    // FILTER USERS (uses searchQuery from search.js)
    const filteredUsers = users.filter(user => {
        return (
            (user.user_code && user.user_code.toLowerCase().includes(searchQuery)) ||
            (user.fname && user.fname.toLowerCase().includes(searchQuery)) ||
            (user.lname && user.lname.toLowerCase().includes(searchQuery)) ||
            (user.username && user.username.toLowerCase().includes(searchQuery)) ||
            (user.role && user.role.toLowerCase().includes(searchQuery)) ||
            (user.email && user.email.toLowerCase().includes(searchQuery))
        );
    });

    // PAGINATION
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedUsers = filteredUsers.slice(start, end);

    // RENDER TABLE
    paginatedUsers.forEach(user => {

        const row = `
<tr>
<td>
    <img 
        src="/POS-GAS/frontend/assets/uploads/users/${user.image || 'default.jpg'}" 
        class="user-img">
</td>

<td>${user.user_code}</td>
<td>${user.fname} ${user.middlename || ""} ${user.lname}</td>
<td>${user.sex || ""}</td>
<td>${user.username}</td>
<td>${user.role}</td>
<td>${user.address || ""}</td>
<td>${user.phone || ""}</td>
<td>${user.email || ""}</td>
<td>${user.date_of_birth || ""}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick="editUser('${user.user_code}')">
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteUser('${user.user_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
`;

        tableBody.innerHTML += row;
    });

    // PAGE INFO
    const totalPages = Math.max(1, Math.ceil(filteredUsers.length / rowsPerPage));
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
}

// NEXT PAGE
function nextPage() {
    const filteredUsers = users.filter(user => {
        return (
            (user.user_code && user.user_code.toLowerCase().includes(searchQuery)) ||
            (user.fname && user.fname.toLowerCase().includes(searchQuery)) ||
            (user.lname && user.lname.toLowerCase().includes(searchQuery)) ||
            (user.username && user.username.toLowerCase().includes(searchQuery)) ||
            (user.role && user.role.toLowerCase().includes(searchQuery)) ||
            (user.email && user.email.toLowerCase().includes(searchQuery))
        );
    });

    const totalPages = Math.ceil(filteredUsers.length / rowsPerPage);

    if (currentPage < totalPages) {
        currentPage++;
        displayUsers();
    }
}

// PREV PAGE
function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayUsers();
    }
}

// ROWS PER PAGE
document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    displayUsers();
});

// BUTTON EVENTS
document.querySelector(".pagination button:first-child").addEventListener("click", prevPage);
document.querySelector(".pagination button:last-child").addEventListener("click", nextPage);

// INITIAL LOAD
displayUsers();
</script>
</body>

</html>