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
        $sql = "INSERT INTO pumps (pump_name, fuel_id, status, created_by, created_at) VALUES(?, ?, ?, ?, NOW())";
        $stmt = $this->conn->prepare($sql);
       $stmt->bind_param("ssss", $pump_number, $fuel_type, $status, $created_by);
        return $stmt->execute();

 }

 public function checkPump($data)
 {
    $pump_number = $data['pump_number'] ?? '';

    $sql = "SELECT * FROM pumps WHERE pump_name =? AND is_deleted = 0";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("s", $pump_number);
    $stmt->execute();
    $stmt->store_result();
    return $stmt->num_rows > 0;
 }

 public function update($data)
 {
    $updated_by = $_SESSION["fname"] ." ". $_SESSION['lname'];
     $pump_name = $data['pump_number'] ?? '';
        $fuel_type = $data['fuel_type'] ?? '';
        $status = $data['status'] ?? '';
        $pump_code = $data['pump_code'] ?? '';

    $sql = "UPDATE pumps SET pump_name =?, fuel_id = ?, status = ?, updated_by = ?, updated_at = NOW() WHERE pump_code =?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sssss",$pump_name, $fuel_type, $status,$updated_by, $pump_code);
    return $stmt->execute();
 }


public function delete($data)
{
    $deleted_by = $_SESSION["fname"] ." ". $_SESSION['lname'];
    $pump_code = $data['pump_code'] ?? '';

    if (empty($pump_code)) return false; // optional safety

    $sql = "UPDATE pumps SET is_deleted = 1, deleted_by = ?, deleted_at = NOW() WHERE pump_code = ?";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss",$deleted_by,$pump_code);

    // Return the result of execute
    return $stmt->execute();
}






















}