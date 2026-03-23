<?php

require_once "../../config/database.php";
require_once "../../backend/controllers/fuelController.php";

require "../../backend/middleware/authMiddleware.php";
AuthMiddleware::check();

$db = new Database();
$conn = $db->connect();

$controller = new FuelController($conn);
$controller->delete();