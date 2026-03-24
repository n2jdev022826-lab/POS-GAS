<?php

class pumpModel{

private $conn;



public function __construct($conn)
{
    $this->conn = $conn; 
}


 public function create($data)
 {
        $created_by = $_SESSION["fname"] ." ". $_SESSION['lname'];
        $pump_number = $data['pump_number'] ?? '';
        $fuel_type = $data['fuel_type'] ?? '';
        $status = $data['status'] ?? '';

        $sql = "INSERT INTO pumps (pump_number, fuel_id, status, created_by, created_at) VALUES(?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("isss",$pump_number,$fuel_type,$status,$created_by);
        return $stmt->execute();

 }

 public function checkPump($data)
 {
    $pump_number = $data['pump_number'] ?? '';

    $sql = "SELECT * FROM pumps WHERE pump_number =? AND is_deleted = 0";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $pump_number);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
 }






















}