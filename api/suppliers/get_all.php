<?php

require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();

$sql = "SELECT supplier_id, supplier_name FROM suppliers ORDER BY supplier_name ASC";
$result = $conn->query($sql);

$suppliers = [];

while ($row = $result->fetch_assoc()) {
    $suppliers[] = $row;
}

echo json_encode($suppliers);