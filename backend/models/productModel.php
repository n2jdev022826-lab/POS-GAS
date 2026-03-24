<?php

class ProductModel{

    private $conn;

    public function __construct($db){
        $this->conn = $db;
    }

public function create($data, $files)
{
    $this->conn->begin_transaction();

    try {

        // =========================
        // 1. UPLOAD IMAGE
        // =========================
        $imageName = '';

        if (!empty($files['image']['name'])) {
            $imageName = time() . '_' . $files['image']['name'];

            move_uploaded_file(
                $files['image']['tmp_name'],
                "../../frontend/assets/uploads/products/" . $imageName
            );
        }

        // =========================
        // 2. INSERT PRODUCT
        // =========================
        $sql = "INSERT INTO products 
        (name, category_id, supplier_id, original_price, selling_price, expiry_date, image, created_by, created_at)
        VALUES (?,?,?,?,?,?,?,?,NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssddsss",
            $data['name'],
            $data['category_id'],
            $data['supplier_id'],
            $data['original_price'],
            $data['selling_price'],
            $data['expiry_date'],
            $imageName,
            $data['created_by']
        );

        $stmt->execute();

        // Get inserted product ID
        $product_id = $this->conn->insert_id;

        // ⚠️ IMPORTANT FIX:
        $result = $this->conn->query("SELECT id FROM products ORDER BY created_at DESC LIMIT 1");
        $row = $result->fetch_assoc();
        $product_id = $row['id'];

        // =========================
        // 3. INSERT INVENTORY LOG
        // =========================
        $sql2 = "INSERT INTO product_inventory_logs 
        (product_id, quantity, supplier_id, created_by, created_at)
        VALUES (?,?,?,?,NOW())";

        $stmt2 = $this->conn->prepare($sql2);

        $stmt2->bind_param(
            "siss",
            $product_id,
            $data['quantity'],
            $data['supplier_id'],
            $data['created_by']
        );

        $stmt2->execute();

        // =========================
        // 4. INSERT MONITORING
        // =========================
        $sql3 = "INSERT INTO product_monitoring 
        (product_id, remaining_quantity)
        VALUES (?,?)";

        $stmt3 = $this->conn->prepare($sql3);

        $stmt3->bind_param(
            "si",
            $product_id,
            $data['quantity']
        );

        $stmt3->execute();

        // =========================
        // COMMIT
        // =========================
        $this->conn->commit();

        return true;

    } catch (Exception $e) {

        $this->conn->rollback();
        return false;
    }
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