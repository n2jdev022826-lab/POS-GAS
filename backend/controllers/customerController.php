<?php

require_once __DIR__ . '/../../backend/models/customerModel.php';

class customerController{

    private $customer;

    public function __construct($conn)
    {
        $this->customer = new customerModel($conn);
    }


    public function create()
{
    $data = $_POST;

    // check duplicate
    if ($this->customer->check($data)) {

        echo json_encode([
            "status" => "error",
            "message" => "Customer with this phone or email already exists"
        ]);
        return;
    }

    // insert if unique
    if ($this->customer->create($data)) {

        echo json_encode([
            "status" => "success",
            "message" => "Customer added successfully"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Failed to add customer"
        ]);
    }
}

   
    

}
