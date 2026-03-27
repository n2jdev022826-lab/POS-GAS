<?php

class categoryModel{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

   
    public function create($data){

        $created_by = $_SESSION['fname'] . " " . $_SESSION["lname"];
        $category_name = $data['category_name'] ?? '';
        $category_description = $data['category_description'] ?? '';

        $sql = "INSERT INTO categories
                (category_name, description, created_by, created_at)
                VALUES (?, ?, ?, NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sss",
            $category_name,
            $category_description,
            $created_by
        );

        return $stmt->execute();
    }

  
    public function checkCategory($data){

        $category_name = $data['category_name'] ?? '';

        $sql = "SELECT * FROM categories 
                WHERE category_name = ? AND is_deleted = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

 
    public function checkCategoryForUpdate($data){

        $category_name = $data['category_name'] ?? '';
        $category_code = $data['category_code'] ?? '';

        $sql = "SELECT * FROM categories 
                WHERE category_name = ? 
                AND category_code != ? 
                AND is_deleted = 0";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $category_name, $category_code);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }


    public function update($data){

        $category_code = $data['category_code'] ?? '';
        $updated_by = $_SESSION['fname'] . " " . $_SESSION["lname"];
        $category_name = $data['category_name'] ?? '';
        $category_description = $data['category_description'] ?? '';

        $sql = "UPDATE categories 
                SET category_name = ?, 
                    description = ?, 
                    updated_by = ?, 
                    updated_at = NOW() 
                WHERE category_code = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param(
            "ssss",
            $category_name,
            $category_description,
            $updated_by,
            $category_code
        );

        return $stmt->execute();
    }

    
    public function delete($data)
    {
        $category_code = $data["category_code"] ?? '';
        $deleted_by = $_SESSION['fname'] . " " . $_SESSION["lname"];

        $sql = "UPDATE categories 
                SET is_deleted = 1, 
                    deleted_by = ?, 
                    deleted_at = NOW() 
                WHERE category_code = ?";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $deleted_by, $category_code);

        return $stmt->execute();
    }

}

?>