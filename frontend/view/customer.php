<?php

require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";

$db = new Database();
$conn = $db->connect();

$customers = [];

$sql = "SELECT * FROM customers WHERE is_deleted = 0";
$result = $conn->query($sql);

while($row = $result->fetch_assoc()){
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


    <!-- ================= TOP BAR ================= -->
    <div class="topbar">
      <div id="datetime"></div>

      <div class="employee-info">
        <div class="employee-name"><?php echo htmlspecialchars($_SESSION['lname'] . ", " . $_SESSION['fname']); ?></div>
        <div id="employee-profile"><img src="/POS-GAS/frontend/assets/uploads/users/<?php echo htmlspecialchars(!empty($_SESSION['image']) ? $_SESSION['image'] : 'default.jpg'); ?>" class="employee-img"></div>
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

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="customer_name" placeholder="Customer Name" required>
                </div>

                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" name="phone" placeholder="Phone Number" required
       pattern="09[0-9]{9}" title="Enter a valid PH mobile number e.g. 09756657044"
       maxlength="11">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="" placeholder="Address" required></textarea>
                </div>


            </div>
             <div class="modal-buttons">
                <button class="btn cancel-btn" onclick="closeModal()">Cancel</button>
                <button class="btn save-btn" type="submit">Save Customer</button>
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

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="customer_name" id="edit_customer_name" required>
                </div>

                <div class="form-group">
                    <label>Contact No.</label>
                    <input type="text" name="phone" placeholder="Phone Number" id="edit_phone" required
       pattern="09[0-9]{9}" title="Enter a valid PH mobile number e.g. 09756657044"
       maxlength="11">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="edit_email" required>
                </div>

                <div class="form-group">
                    <label>Address</label>
                    <textarea name="address" id="edit_address" required></textarea>
                </div>

            </div>

            <div class="modal-buttons">
                <button type="button" class="btn cancel-btn" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn save-btn">Update Customer</button>
            </div>

        </form>

    </div>

</div>

    <script src="/POS-GAS/frontend/js/date-time.js"></script>
    <script src="/POS-GAS/frontend/js/print.js"></script>
    <script src="/POS-GAS/frontend/js/alert.js"></script>
   


<script>

    function openModal() {
    const modal = document.getElementById("customerModal");
    modal.style.display = "flex";
    document.body.classList.add("modal-open"); // optional: prevent scrolling
}

function closeModal() {
    const modal = document.getElementById("customerModal");
    modal.style.display = "none";
    document.body.classList.remove("modal-open");
}

// Get all phone inputs
const phoneInputs = document.querySelectorAll('input[name="phone"]');

phoneInputs.forEach(input => {
    input.addEventListener('input', function() {
        // Remove any non-digit character
        this.value = this.value.replace(/\D/g, '');

        // Limit to 11 digits
        if(this.value.length > 11) {
            this.value = this.value.slice(0, 11);
        }
    });
});

const customers = <?php echo json_encode($customers); ?>;

let currentPage = 1;
let rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);

// ================= DISPLAY =================
function displayCustomers() {

    const tableBody = document.getElementById("tableBody");
    tableBody.innerHTML = "";

    // ✅ SEARCH
    const search = document.getElementById("searchInput").value.toLowerCase();

    const filteredCustomers = customers.filter(customer =>
        (customer.customer_code && customer.customer_code.toLowerCase().includes(search)) ||
        (customer.customer_name && customer.customer_name.toLowerCase().includes(search)) ||
        (customer.phone && customer.phone.toLowerCase().includes(search)) ||
        (customer.email && customer.email.toLowerCase().includes(search))
    );

    // ✅ PAGINATION
    const start = (currentPage - 1) * rowsPerPage;
    const end = start + rowsPerPage;
    const paginatedCustomers = filteredCustomers.slice(start, end);

    paginatedCustomers.forEach(customer => {

        const row = `
<tr>
<td>${customer.customer_code}</td>
<td>${customer.customer_name}</td>
<td>${customer.phone}</td>
<td>${customer.address}</td>
<td>${customer.email}</td>
<td>${customer.created_at}</td>

<td class="action-buttons">
    <button class="icon-btn edit-btn"
        onclick='openEditModal(${JSON.stringify(customer)})'>
        <img src="/POS-GAS/frontend/assets/icons/edit.png">
        <span>EDIT</span>
    </button>

    <button class="icon-btn delete-btn"
        onclick="deleteCustomer('${customer.customer_code}')">
        <img src="/POS-GAS/frontend/assets/icons/delete.png">
        <span>DELETE</span>
    </button>
</td>
</tr>
        `;

        tableBody.innerHTML += row;
    });

    const totalPages = Math.max(1, Math.ceil(filteredCustomers.length / rowsPerPage));
    document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${totalPages}`;
}

// ================= PAGINATION =================
function nextPage() {
    const search = document.getElementById("searchInput").value.toLowerCase();

    const filteredCustomers = customers.filter(customer =>
        (customer.customer_name && customer.customer_name.toLowerCase().includes(search))
    );

    const totalPages = Math.ceil(filteredCustomers.length / rowsPerPage);

    if (currentPage < totalPages) {
        currentPage++;
        displayCustomers();
    }
}

function prevPage() {
    if (currentPage > 1) {
        currentPage--;
        displayCustomers();
    }
}

document.getElementById("rowsPerPage").addEventListener("change", function () {
    rowsPerPage = parseInt(this.value);
    currentPage = 1;
    displayCustomers();
});

// ================= SEARCH (DEBOUNCE) =================
let searchTimeout;

document.getElementById("searchInput").addEventListener("input", function () {
    clearTimeout(searchTimeout);

    searchTimeout = setTimeout(() => {
        currentPage = 1;
        displayCustomers();
    }, 300);
});

// ================= ADD CUSTOMER =================
document.getElementById("addCustomer").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/customer/create.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showAlert("success", "Success", data.message);

        if (data.status === "success") {
            setTimeout(() => location.reload(), 1000);
        }else {
            showAlert("error", "Failed", data.message);
          }
    })
      .catch(err => {
            console.error(err);
             showAlert("error", "Failed", "Something went wrong!");
        });
});

// ================= DELETE =================
function deleteCustomer(code) {

    if (!confirm("Delete this customer?")) return;

    const formData = new FormData();
    formData.append("customer_code", code);

    fetch("http://localhost/POS-GAS/api/customer/delete.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showAlert("success", "Success", data.message);

        if (data.status === "success") {
            setTimeout(() => location.reload(), 1000);
        }else {
            showAlert("error", "Failed", data.message);
          }
    })
      .catch(err => {
            console.error(err);
             showAlert("error", "Failed", "Something went wrong!");
        });
}

// ================= EDIT MODAL =================
function openEditModal(customer) {
    document.getElementById("editCustomerModal").style.display = "flex";

    document.getElementById("edit_customer_code").value = customer.customer_code;
    document.getElementById("edit_customer_name").value = customer.customer_name;
    document.getElementById("edit_phone").value = customer.phone;
    document.getElementById("edit_email").value = customer.email;
    document.getElementById("edit_address").value = customer.address;
}

function closeEditModal() {
    document.getElementById("editCustomerModal").style.display = "none";
}

// ================= UPDATE =================
document.getElementById("editCustomerForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("http://localhost/POS-GAS/api/customer/update.php", {
        method: "POST",
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        showAlert("success", "Success", data.message);

        if (data.status === "success") {
            setTimeout(() => location.reload(), 1000);
        }else {
            showAlert("error", "Failed", data.message);
          }
    })
       .catch(err => {
            console.error(err);
             showAlert("error", "Failed", "Something went wrong!");
        });
});

// ================= INIT =================
displayCustomers();
</script>

</body>

</html>

