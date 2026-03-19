<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

require "../../config/database.php";
require "../../backend/controllers/productController.php";

$db = new Database();
$conn = $db->connect();

$controller = new ProductController($conn);
$controller->delete();