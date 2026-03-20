<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

require_once "../../config/database.php";
require_once "../../backend/controllers/userController.php";

$db = new Database();
$conn = $db->connect();

$controller = new UserController($conn);


try {
    $controller->create();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred: " . $e->getMessage()
    ]);
}