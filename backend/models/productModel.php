<?php

class ProductModel{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

    public function create($data){

        $sql = "INSERT INTO Products
        (
        product_name,
        generic_name,
        category,
        supplier,
        purchase_price,
        selling_price,
        stock_quantity,
        expiry_date,
        created_by
        )
        VALUES (?,?,?,?,?,?,?,?,?)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssddiss",
            $data['product_name'],
            $data['generic_name'],
            $data['category'],
            $data['supplier'],
            $data['purchase_price'],
            $data['selling_price'],
            $data['stock_quantity'],
            $data['expiry_date'],
            $data['created_by']
        );

        return $stmt->execute();
    }


    public function update($data){

        $sql = "UPDATE Products SET
        product_name=?,
        generic_name=?,
        category=?,
        supplier=?,
        purchase_price=?,
        selling_price=?,
        stock_quantity=?,
        expiry_date=?,
        updated_by=?
        WHERE product_id=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ssssddisss",
            $data['product_name'],
            $data['generic_name'],
            $data['category'],
            $data['supplier'],
            $data['purchase_price'],
            $data['selling_price'],
            $data['stock_quantity'],
            $data['expiry_date'],
            $data['updated_by'],
            $data['product_id']
        );

        return $stmt->execute();
    }


    public function delete($data){

        $sql = "UPDATE Products
        SET is_deleted = 1,
        deleted_by = ?
        WHERE product_id = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $data['deleted_by'],
            $data['product_id']
        );

        return $stmt->execute();
    }

}