<?php

class SupplierModel
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {

        $created_by = $_SESSION['user_id'];

        $supplier_name = $data['supplier_name'] ?? '';
        $contact_name = $data['contact_name'] ?? '';
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';
        $address = $data['address'] ?? '';

        $sql = "INSERT INTO suppliers
        (supplier_name,contact_name,phone,email,address,created_by)
        VALUES (?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
           $supplier_name,
           $contact_name,
           $phone,
           $email,
           $address,
           $created_by
            
          
        );

        return $stmt->execute();
    }


    public function suppliercheck($data){

        $supplier_name = $data['supplier_name'] ?? '';

        $sql = "SELECT * FROM suppliers WHERE supplier_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $supplier_name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;

    }
}
