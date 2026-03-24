<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

require_once "../../config/database.php";
require_once "../../backend/controllers/pumpController.php";

$db = new Database();
$conn = $db->connect();

$controller = new pumpController($conn);
$controller->create();