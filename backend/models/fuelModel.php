<?php


class FuelModel
{

    private $conn;


    public function __construct($db)
    {
        $this->conn = $db;
    }


    public function createFuel($data)
    {
        $name = $data['fuel_name'] ?? '';
        $price = $data['fuel_price'] ?? '';

        $sql = "INSERT INTO fuel (name, price_per_liter) VALUES (?, ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sd", $name, $price);

        return $stmt->execute();
    }
    
}