<?php



require_once "../../config/database.php";
require_once "../../backend/middleware/route.php";
require_once "../../backend/middleware/auth.php";




$db = new Database();
$conn = $db->connect();


$users =  [];

$sql = "SELECT * FROM categories WHERE is_deleted = 0";

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
    <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
    <title>Document</title>
</head>
<body>
    <form id="addCategoryForm">
      <div class="modal-grid">

             <div class="input-group">
                                <label>CATEGORY NAME</label>
                                <input type="text" name="category_name" required>
                            </div>

                            <div class="input-group">
                                <label>CATEGORY DESCRIPTION</label>
                                <input type="text" name="category_description" required>
                            </div>

                            <div class="modal-buttons">
                                <button type="submit" class="save-btn">+ SAVE</button>
                            </div>

                </div>

                <div class="modal-buttons">
                    <button type="button" class="cancel-btn" onclick="closeUserModal()">Cancel</button>
                    <button type="submit" class="save-btn">Save User</button>
                </div>
    </form>



<h2>User List</h2>

<table border="1" cellpadding="10">
    <thead>
        <tr>
            <th>Category Code</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($users)): ?>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['category_code']) ?></td>
                    <td><?= htmlspecialchars($user['category_name']) ?></td>
                    <td><?= htmlspecialchars($user['description']) ?></td>
                    <td> <?= date("F d, Y h:i A", strtotime($user['created_at'])) ?></td>
                    <td>
    <button 
        class="delete-btn" 
        data-code="<?= htmlspecialchars($user['category_code']) ?>">
        Delete
    </button>
</td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="8">No users found</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>



  
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

 <script>
      document.addEventListener("click", function(e) {
    if (e.target.classList.contains("delete-btn")) {

        const code = e.target.getAttribute("data-code");

        if (!confirm("Are you sure you want to delete this category?")) return;

        const formData = new FormData();
        formData.append("category_code", code); // ✅ FIXED

        fetch("http://localhost/POS-GAS/api/categories/delete.php", {
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

