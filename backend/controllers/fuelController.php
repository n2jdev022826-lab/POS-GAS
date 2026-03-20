<?php

require_once __DIR__ . '/../../backend/models/fuelModel.php';

class FuelController
{

    private $fuel;

    public function __construct($db) 
    {
        $this->fuel = new FuelModel($db);
    }

    public function create()
    {

        $data = $_POST;

         if ($this->fuel->createFuel($data)) {

            echo json_encode([
                "status" => "success",
                "message" => "Fuel added successfully"
            ]);
        } else {

            echo json_encode([
                "status" => "error",
                "message" => "Failed to add fuel"
            ]);
        }
    }
}