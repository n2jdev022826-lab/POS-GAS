<?php
require_once "../../backend/middleware/auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
     <link rel="stylesheet" href="/POS-GAS/frontend/css/alert.css">
</head>
<body>
    <form id="addCategory">
        <input type="text" name="category_name" id="category_name" placeholder="Enter Category Name" required>
        <textarea name="category_description" id="category_description" placeholder="Enter Category Description"></textarea>
        <button type="submit">Submit</button>
    </form>

  
</body>
</html>

  <script src="/POS-GAS/frontend/js/alert.js"></script>

<script>
    document.getElementById("addCategory").addEventListener("submit", function(e){
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
            }   
           
            

        })
        .catch(err => {
            console.error("Error:", err);
            alert("An error occurred while adding the category.");
        })
    })
    
</script>