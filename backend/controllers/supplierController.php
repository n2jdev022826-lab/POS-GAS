<?php

require_once __DIR__ . '/../../backend/models/supplierModel.php';

class SupplierController {

    private $supplier;

    public function __construct($db) {
        $this->supplier = new SupplierModel($db);
    }

    public function create() {
        $data = $_POST;

        $data['created_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->supplier->create($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Supplier added successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to add supplier"
            ]);
        }
    }

    public function update() {
        $data = $_POST;

        $data['updated_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->supplier->update($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Supplier updated successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update supplier"
            ]);
        }
    }

    public function delete($data) {
        if (!isset($data['supplier_code'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Supplier code is required"
            ]);
            return;
        }

        $deleted_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->supplier->delete($data['supplier_code'], $deleted_by)) {
            echo json_encode([
                "status" => "success",
                "message" => "Supplier deleted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete supplier"
            ]);
        }
    }
}