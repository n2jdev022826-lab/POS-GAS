<?php

require_once "../../config/database.php";
require_once "../../backend/controllers/fuelController.php";

$db = new Database();
$conn = $db->connect();

$controller = new FuelController($conn);
$controller->update();