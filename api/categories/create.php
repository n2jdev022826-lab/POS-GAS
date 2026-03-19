<?php

header("Content-Type: application/json");

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

require_once "../../config/database.php";
require_once "../../backend/controllers/categoryController.php";

$db = new Database();
$conn = $db->connect();

$controller = new categoryController($conn);
$controller->create();