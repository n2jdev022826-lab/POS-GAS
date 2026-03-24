<?php

require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();

$sql = "SELECT category_id, category_name FROM categories ORDER BY category_name ASC";
$result = $conn->query($sql);

$categories = [];

while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

echo json_encode($categories);