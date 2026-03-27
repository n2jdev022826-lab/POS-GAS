<?php

class pumpModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ================= CREATE =================
    public function create($data)
    {
        $created_by = $data['created_by'] ?? '';

        $sql = "INSERT INTO pumps 
        (pump_name, fuel_id, status, created_by, created_at)
        VALUES (?, ?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssss",
            $data['pump_number'],
            $data['fuel_id'],
            $data['status'],
            $created_by
        );

        return $stmt->execute();
    }

    // ================= CHECK DUPLICATE =================
    public function checkPump($data)
    {
        $pump_name = $data['pump_number'] ?? '';

        $sql = "SELECT pump_code FROM pumps 
                WHERE pump_name = ? 
                AND is_deleted = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $pump_name);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    // ================= UPDATE =================
    public function update($data)
    {
        $updated_by = $data['updated_by'] ?? '';

        $sql = "UPDATE pumps 
        SET pump_name=?, 
            fuel_id=?, 
            status=?, 
            updated_by=?, 
            updated_at=NOW()
        WHERE pump_code=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssss",
            $data['pump_number'],
            $data['fuel_id'],
            $data['status'],
            $updated_by,
            $data['pump_code']
        );

        return $stmt->execute();
    }

    // ================= DELETE =================
    public function delete($pump_code, $deleted_by)
    {
        $sql = "UPDATE pumps 
        SET is_deleted = 1,
            deleted_by = ?,
            deleted_at = NOW()
        WHERE pump_code = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ss", $deleted_by, $pump_code);

        return $stmt->execute();
    }
}