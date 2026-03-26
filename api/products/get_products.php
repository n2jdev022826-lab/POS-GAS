<?php
require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();

$sql = "SELECT 
    p.product_code,
    p.name,
    p.selling_price,
    p.expiry_date,
    p.image,
    p.category_id, 

    pm.remaining_quantity AS stock

FROM products p

LEFT JOIN product_monitoring pm 
    ON p.id = pm.product_id

WHERE p.is_deleted = 0
ORDER BY p.created_at DESC";

$result = $conn->query($sql);

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    "status" => "success",
    "data" => $products
]);

$conn->close();