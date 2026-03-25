<?php
require_once "../../backend/middleware/auth.php";
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
        <button type="submit">Save Customer</button>
    </form>
</body>

</html>

<script>
    document.getElementById("addCustomer").addEventListener("submit", function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        fetch("http://localhost/POS-GAS/api/customer/create.php", {
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