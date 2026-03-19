<?php

require_once __DIR__."/../../backend/models/productModel.php";

class ProductController{

    private $product;

    public function __construct($db){
        $this->product = new ProductModel($db);
    }

    public function create(){

        $data = $_POST;

        if($this->product->create($data)){

            echo json_encode([
                "status"=>"success",
                "message"=>"Product added successfully"
            ]);

        }else{

            echo json_encode([
                "status"=>"error",
                "message"=>"Failed to add product"
            ]);

        }

    }


    public function update(){

        $data = $_POST;

        if($this->product->update($data)){

            echo json_encode([
                "status"=>"success",
                "message"=>"Product updated successfully"
            ]);

        }else{

            echo json_encode([
                "status"=>"error",
                "message"=>"Update failed"
            ]);

        }

    }


    public function delete(){

        $data = json_decode(file_get_contents("php://input"),true);

        if($this->product->delete($data)){

            echo json_encode([
                "status"=>"success",
                "message"=>"Product deleted"
            ]);

        }else{

            echo json_encode([
                "status"=>"error",
                "message"=>"Delete failed"
            ]);

        }

    }

}