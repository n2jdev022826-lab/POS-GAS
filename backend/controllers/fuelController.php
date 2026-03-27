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

        $result = $this->fuel->createFuel($data);

        if (!is_array($result)) {
            $result = [
                "status" => "error",
                "message" => "Unexpected error occurred"
            ];
        }

        echo json_encode($result);
    }


    // ================= UPDATE =================
    public function update()
    {
        $data = $_POST;

        $result = $this->fuel->updateFuel($data);

        echo json_encode($result);
    }


    // ================= DELETE =================
    public function delete()
    {
        // receive JSON body
        $data = json_decode(file_get_contents("php://input"), true);

        $result = $this->fuel->deleteFuel($data);

        echo json_encode($result);
    }

    // ================= REFILL =================
    public function refill()
    {
        $data = $_POST;

        $result = $this->fuel->refillFuel($data);

        echo json_encode($result);

    }
}
