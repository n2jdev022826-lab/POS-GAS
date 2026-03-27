<?php

require_once __DIR__ . '/../../backend/models/pumpModel.php';

class pumpController
{
    private $pump;

    public function __construct($db)
    {
        $this->pump = new pumpModel($db);
    }

    // ================= CREATE =================
    public function create()
    {
        $data = $_POST;

        // ADD CREATED BY
        $data['created_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        // CHECK DUPLICATE
        if ($this->pump->checkPump($data)) {
            echo json_encode([
                "status" => "error",
                "message" => "Pump already exists"
            ]);
            return;
        }

        if ($this->pump->create($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Pump added successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to add pump"
            ]);
        }
    }

    // ================= UPDATE =================
    public function update()
    {
        $data = $_POST;

        // ADD UPDATED BY
        $data['updated_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->pump->update($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Pump updated successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update pump"
            ]);
        }
    }

    // ================= DELETE =================
    public function delete()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (!isset($data['pump_code'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Pump code is required"
            ]);
            return;
        }

        $deleted_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->pump->delete($data['pump_code'], $deleted_by)) {
            echo json_encode([
                "status" => "success",
                "message" => "Pump deleted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete pump"
            ]);
        }
    }
}