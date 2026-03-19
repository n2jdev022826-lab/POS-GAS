<?php

class customerModel{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function create($data){

    $created_by = $_SESSION['user_id'];

    $customer_name = $data['customer_name'] ?? '';
    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';
    $address = $data['address'] ?? '';

    $sql = "INSERT INTO customers
            (customer_name, phone, email, address, created_by)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param(
        "sssss",
        $customer_name,
        $phone,
        $email,
        $address,
        $created_by
    );

    return $stmt->execute();
}

    public function check($data){

    $phone = $data['phone'] ?? '';
    $email = $data['email'] ?? '';

    $sql = "SELECT * FROM customers WHERE phone = ? OR email = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $phone, $email);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;

    }


}









?>