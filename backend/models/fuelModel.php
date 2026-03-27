<?php

class FuelModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // ================= CREATE FUEL =================
    public function createFuel($data)
    {
        $created_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
        $name = $data['name'] ?? '';
        $price = $data['price_per_liter'] ?? 0;
        $supplier_id = $data['supplier_id'] ?? null;

        // 🔍 CHECK DUPLICATE
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

        // ✅ INSERT FUEL
        $sql = "INSERT INTO fuels 
                (name, price_per_liter, created_by, created_at) 
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sds", $name, $price, $created_by);

        if ($stmt->execute()) {

            // ✅ GET GENERATED VARCHAR ID (IMPORTANT FIX)
            $getIdSql = "SELECT id FROM fuels 
                         WHERE name = ? 
                         ORDER BY created_at DESC LIMIT 1";

            $getStmt = $this->conn->prepare($getIdSql);
            $getStmt->bind_param("s", $name);
            $getStmt->execute();
            $res = $getStmt->get_result();
            $row = $res->fetch_assoc();

            $fuel_id = $row['id'];

            // ✅ INSERT INITIAL INVENTORY LOG (0 liters)
            if ($supplier_id) {
                $inventory_code = uniqid("INV-");

                $sql2 = "INSERT INTO fuel_inventory_logs 
                        (inventory_code, fuel_id, fuel_liters, supplier_id, created_at, created_by) 
                        VALUES (?, ?, 0, ?, NOW(), ?)";

                $stmt2 = $this->conn->prepare($sql2);
                $stmt2->bind_param("ssss", $inventory_code, $fuel_id, $supplier_id, $created_by);
                $stmt2->execute();
            }

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
        $fuel_id = $data['fuel_id'] ?? '';
        $name = $data['name'] ?? '';
        $price = $data['price_per_liter'] ?? 0;

        if (empty($fuel_id)) {
            return [
                "status" => "error",
                "message" => "Invalid fuel ID"
            ];
        }

        // 🔍 CHECK DUPLICATE NAME
        $checkSql = "SELECT id FROM fuels 
                     WHERE name = ? AND id != ? AND is_deleted = 0";

        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $name, $fuel_id);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {
            return [
                "status" => "error",
                "message" => "Fuel name already exists"
            ];
        }

        // ✅ UPDATE
        $sql = "UPDATE fuels 
                SET name = ?, price_per_liter = ?, 
                    updated_by = ?, updated_at = NOW()
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdss", $name, $price, $updated_by, $fuel_id);

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

    // ================= DELETE FUEL =================
    public function deleteFuel($data)
    {
        $deleted_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
        $fuel_id = $data['fuel_id'] ?? '';

        if (empty($fuel_id)) {
            return [
                "status" => "error",
                "message" => "Invalid fuel ID"
            ];
        }

        $sql = "UPDATE fuels 
                SET is_deleted = 1, 
                    deleted_by = ?, 
                    deleted_at = NOW() 
                WHERE id = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $deleted_by, $fuel_id);

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

    // ================= REFILL FUEL =================
    public function refillFuel($data)
    {
        $created_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
        $fuel_id = $data['fuel_id'] ?? '';
        $liters_added = $data['liters_added'] ?? 0;

        if (empty($fuel_id)) {
            return ["status" => "error", "message" => "Invalid fuel ID"];
        }

        if (!is_numeric($liters_added) || $liters_added <= 0) {
            return ["status" => "error", "message" => "Invalid liters"];
        }

        // ✅ DEBUG: CHECK INPUT
        if (!$fuel_id) {
            return ["status" => "error", "message" => "Fuel ID missing"];
        }

        // GET supplier
        $sql = "SELECT supplier_id 
            FROM fuel_inventory_logs 
            WHERE fuel_id = ? 
            ORDER BY created_at DESC 
            LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $fuel_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if (!$row) {
            return ["status" => "error", "message" => "No supplier found"];
        }

        $supplier_id = $row['supplier_id'];

        $inventory_code = uniqid("INV-");

        $sql = "INSERT INTO fuel_inventory_logs 
            (inventory_code, fuel_id, fuel_liters, supplier_id, created_at, created_by) 
            VALUES (?, ?, ?, ?, NOW(), ?)";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssdss", $inventory_code, $fuel_id, $liters_added, $supplier_id, $created_by);

        // 🔥 THIS IS THE KEY LINE
        if (!$stmt->execute()) {
            return [
                "status" => "error",
                "message" => "SQL ERROR: " . $stmt->error
            ];
        }

        return [
            "status" => "success",
            "message" => "Fuel refilled successfully"
        ];
    }
}
