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
    $customers[] = $row;
}




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form id="addCustomer">
        <input type="text" name="customer_name" placeholder="Customer Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>
        <textarea name="address" id="" placeholder="Address" required></textarea>
        <input type="text" name="customer_code" value="CUSTCODE-00000002">
        <button type="submit">Save Customer</button>
    </form>






    <h2>Customer List</h2>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Customer Code</th>
            <th>Full Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($customers)): ?>
            <?php foreach ($customers as $customer): ?>
                <tr>
                   
                    <td><?= htmlspecialchars($customer['customer_code']) ?></td>
                    <td><?= htmlspecialchars($customer['customer_name']) ?></td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                     <td><?= htmlspecialchars($customer['email']) ?></td>
                    <td><?= date("F d, Y h:i A", strtotime($customer['created_at'])) ?></td>
                    <td>
                        <button class="delete-btn" data-id="<?= $customer['customer_code'] ?>">
                            Delete
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">No Customer found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

</body>

</html>

<script>
    document.getElementById("addCustomer").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("http://localhost/POS-GAS/api/customer/update.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log("Server Response:", data);
                alert(data.message);
            })
            .catch(err => {
                console.error("Error:", err);
                alert("An error occurred while adding the customer.");
            })
    })
</script>



 <script>
     document.addEventListener("click", function(e) {
    if (e.target.classList.contains("delete-btn")) {

        const code = e.target.getAttribute("data-id"); // ✅ FIXED

        if (!confirm("Are you sure you want to delete this customer?")) return;

        const formData = new FormData();
        formData.append("customer_code", code);

        fetch("http://localhost/POS-GAS/api/customer/delete.php", {
            method: "POST",
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            console.log("Server Response:", data);
            alert(data.message);

            if (data.status === "success") {
                location.reload();
            }
        })
        .catch(err => {
            console.error(err);
            alert("Something went wrong!");
        });
    }
});
    </script>