<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();


require "../../config/database.php";
require "../../backend/controllers/customerController.php";

$db = new Database();
$conn = $db->connect();

$controller = new customerController($conn);
$controller->create();



?>