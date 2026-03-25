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


  public function update($data, $files)
{
    try {

        $product_code = $data['product_code'];

        // =========================
        // 1. GET OLD IMAGE
        // =========================
        $oldImage = '';
        $getSql = "SELECT image FROM products WHERE product_code = ?";
        $getStmt = $this->conn->prepare($getSql);
        $getStmt->bind_param("s", $product_code);
        $getStmt->execute();
        $result = $getStmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $oldImage = $row['image'];
        }

        // =========================
        // 2. HANDLE IMAGE
        // =========================
        $imageName = $oldImage;

        if (!empty($files['image']['name'])) {

            $imageName = time() . '_' . $files['image']['name'];
            $target = "../../frontend/assets/uploads/products/" . $imageName;

            if (!move_uploaded_file($files['image']['tmp_name'], $target)) {
                return false;
            }
        }

        // =========================
        // 3. UPDATE PRODUCT
        // =========================
        $updated_by = $data['updated_by'] ?? '';

        $sql = "UPDATE products SET
            name=?,
            category_id=?,
            supplier_id=?,
            original_price=?,
            selling_price=?,
            expiry_date=?,
            image=?,
            updated_by=?,
            updated_at=NOW()
            WHERE product_code=?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssddssss",
            $data['name'],
            $data['category_id'],
            $data['supplier_id'],
            $data['original_price'],
            $data['selling_price'],
            $data['expiry_date'],
            $imageName,
            $updated_by,
            $product_code
        );

        if ($stmt->execute()) {

            // =========================
            // 4. DELETE OLD IMAGE
            // =========================
            if (!empty($files['image']['name']) && $oldImage) {
                $oldPath = "../../frontend/assets/uploads/products/" . $oldImage;

                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            return true;
        }

        return false;

    } catch (Exception $e) {
        return false;
    }
}

public function delete($data)
{
    try {

        $deleted_by = $data['deleted_by'] ?? '';

        $sql = "UPDATE products
                SET is_deleted = 1,
                    deleted_by = ?,
                    deleted_at = NOW()
                WHERE product_code = ?";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "ss",
            $deleted_by,
            $data['product_code']
        );

        return $stmt->execute();

    } catch (Exception $e) {
        return false;
    }
}

}