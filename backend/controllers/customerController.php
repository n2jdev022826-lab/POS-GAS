<?php

require_once __DIR__ . '/../../backend/models/customerModel.php';

class customerController{

    private $customer;

    public function __construct($conn)
    {
        $this->customer = new customerModel($conn);
    }

     private function isEmpty($data)
{
    $name = $data['customer_name'] ?? '';
    return empty(trim($name));
}



    public function create()
{
    $data = $_POST;



      if ($this->isEmpty($data)) {
        echo json_encode([
            "status" => "error",
            "message" => "Customer name is required"
        ]);
        return;
    }


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

public function update()
{
    $data = $_POST;

    if($this->customer->update($data)){
         echo json_encode([
            "status" => "success",
            "message" => "Customer updated successfully"
        ]);
    }else{
         echo json_encode([
            "status" => "error",
            "message" => "Failed to update customer"
        ]);
    }
}

public function delete()
{
        $data = $_POST;
    if($this->customer->delete($data)){
         echo json_encode([
            "status" => "success",
            "message" => "Customer deleted successfully"
        ]);
    }else{
         echo json_encode([
            "status" => "error",
            "message" => "Failed to delete customer"
        ]);
    }
}

   
    

}
