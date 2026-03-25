<?php

require_once __DIR__ . "/../../backend/models/productModel.php";

class ProductController
{

    private $product;

    public function __construct($db)
    {
        $this->product = new ProductModel($db);
    }

    public function create()
    {
        $data = $_POST;
        $data['created_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        $result = $this->product->create($data, $_FILES);

        if ($result) {
            echo json_encode([
                "status" => "success",
                "message" => "Product added successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to add product"
            ]);
        }
    }

    public function update()
    {
        $data = $_POST;
        $data['updated_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->product->update($data, $_FILES)) {

            echo json_encode([
                "status" => "success",
                "message" => "Product updated successfully"
            ]);
        } else {

            echo json_encode([
                "status" => "error",
                "message" => "Update failed"
            ]);
        }
    }


    public function delete()
    {
        $data = json_decode(file_get_contents("php://input"), true);

        $data['deleted_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

        if ($this->product->delete($data)) {

            echo json_encode([
                "status" => "success",
                "message" => "Product deleted"
            ]);
        } else {

            echo json_encode([
                "status" => "error",
                "message" => "Delete failed"
            ]);
        }
    }
}
