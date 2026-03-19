<?php

require_once __DIR__ . '/../../backend/models/supplierModel.php';

class SupplierController
{

    private $user;

    public function __construct($db)
    {
        $this->user = new SupplierModel($db);
    }

    public function create()
    {

        $data = $_POST;

        if($this->user->suppliercheck($data)){
             echo json_encode([
            "status" => "error",
            "message" => "Supplier Name Already Exist"
        ]);
        return;

        }else{
            if ($this->user->create($data)) {

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
    }
}
