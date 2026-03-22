<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

require_once "../../config/database.php";
require_once "../../backend/controllers/supplierController.php";

$db = new Database();
$conn = $db->connect();

$controller = new SupplierController($conn);

try {
    $controller->update();
} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}