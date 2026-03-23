<?php

class SupplierModel {

    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // ================= CREATE =================
    public function create($data) {

        $created_by = $data['created_by'] ?? '';

        $sql = "INSERT INTO suppliers
        (supplier_name, contact_name, phone, email, address, created_by, created_at)
        VALUES (?,?,?,?,?,?,NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssss",
            $data['supplier_name'],
            $data['contact_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $created_by
        );

        return $stmt->execute();
    }

    // ================= UPDATE =================
    public function update($data) {

        $updated_by = $data['updated_by'] ?? '';

        $sql = "UPDATE suppliers SET
            supplier_name=?,
            contact_name=?,
            phone=?,
            email=?,
            address=?,
            updated_by=?,
            updated_at=NOW()
        WHERE supplier_code=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssssss",
            $data['supplier_name'],
            $data['contact_name'],
            $data['phone'],
            $data['email'],
            $data['address'],
            $updated_by,
            $data['supplier_code']
        );

        return $stmt->execute();
    }

    // ================= DELETE (SOFT DELETE) =================
    public function delete($supplier_code, $deleted_by) {

        $sql = "UPDATE suppliers 
        SET is_deleted = 1,
            deleted_by = ?,
            deleted_at = NOW()
        WHERE supplier_code = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param("ss", $deleted_by, $supplier_code);

        return $stmt->execute();
    }

    public function checkSupplier($data){

        $supplier_name = $_POST["supplier_name"];

        $sql = "SELECT * FROM suppliers WHERE supplier_name = ? ";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $supplier_name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;


    }
}