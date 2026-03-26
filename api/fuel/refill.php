<?php

session_start();

require "../../config/database.php";

$db = new Database();
$conn = $db->connect();

$created_by = $_SESSION["fname"] ." ". $_SESSION["lname"];
$fuel_id = $_POST['fuel_id'];
$liters = (float) $_POST['liters_added'];
$supplier_id = $_POST['supplier_id'] ?? null; // optional

try {

    $stmt = $conn->prepare("
        INSERT INTO fuel_inventory_logs 
        (fuel_id, fuel_liters, supplier_id, created_by, created_at)
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("sdss", $fuel_id, $liters, $supplier_id, $created_by);
    $stmt->execute();

    echo json_encode([
        "status" => "success",
        "message" => "Fuel refilled successfully"
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}