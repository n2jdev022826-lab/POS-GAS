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
    $created_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
    $name = $data['name'] ?? '';
    $price = $data['price_per_liter'] ?? 0;

    // 🔍 CHECK IF NAME ALREADY EXISTS
    $checkSql = "SELECT id FROM fuels WHERE name = ? AND is_deleted = 0";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->bind_param("s", $name);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        return [
            "status" => "error",
            "message" => "Fuel name already exists"
        ];
    }

    // ✅ INSERT IF NOT EXISTS
    $sql = "INSERT INTO fuels (name, price_per_liter,created_by) VALUES (?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sds", $name, $price,$created_by);

    if ($stmt->execute()) {
        return [
            "status" => "success",
            "message" => "Fuel added successfully"
        ];
    }

    return [
        "status" => "error",
        "message" => "Failed to add fuel"
    ];
}

// ================= UPDATE FUEL =================
public function updateFuel($data)
{
    $updated_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
    $fuel_code = $data['fuel_code'] ?? '';
    $name = $data['name'] ?? '';
    $price = $data['price_per_liter'] ?? 0;

    // 🔍 Check duplicate name (exclude current fuel)
    $checkSql = "SELECT id FROM fuels 
                 WHERE name = ? AND fuel_code != ? AND is_deleted = 0";
    $checkStmt = $this->conn->prepare($checkSql);
    $checkStmt->bind_param("ss", $name, $fuel_code);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {
        return [
            "status" => "error",
            "message" => "Fuel name already exists"
        ];
    }

    // ✅ Update
    $sql = "UPDATE fuels 
            SET name = ?, price_per_liter = ?, updated_by = ? , updated_at = NOW()
            WHERE fuel_code = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("sdss", $name, $price, $updated_by, $fuel_code);

    if ($stmt->execute()) {
        return [
            "status" => "success",
            "message" => "Fuel updated successfully"
        ];
    }

    return [
        "status" => "error",
        "message" => "Failed to update fuel"
    ];
}


// ================= DELETE FUEL (SOFT DELETE) =================
public function deleteFuel($data)
{
    $deleted_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
    $fuel_code = $data['fuel_code'] ?? '';

    $sql = "UPDATE fuels SET is_deleted = 1, deleted_by = ?, deleted_at = NOW() WHERE fuel_code = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("ss", $deleted_by, $fuel_code);

    if ($stmt->execute()) {
        return [
            "status" => "success",
            "message" => "Fuel deleted successfully"
        ];
    }

    return [
        "status" => "error",
        "message" => "Failed to delete fuel"
    ];
}
}