<?php

class categoryModel{

    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }


    public function create($data){

   $created_by =  $_SESSION['fname'] . " ". $_SESSION["lname"];
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

        $sql = "SELECT * FROM categories WHERE category_name = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $category_name);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;

    }

    public function update($data){
        $sql = "UPDATE ";
    }


}









?>