<?php

require_once __DIR__ . '/../../backend/models/categoryModel.php';

class categoryController
{
    private $category;

    public function __construct($conn)
    {
        $this->category = new categoryModel($conn);
    }

   
    public function create()
    {
        $data = $_POST;

        if (empty($data['category_name'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Category name is required"
            ]);
            return;
        }

        
        if ($this->category->checkCategory($data)) {
            echo json_encode([
                "status" => "error",
                "message" => "Category Name Already Exist"
            ]);
            return;
        }

        if ($this->category->create($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Category added successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to add category"
            ]);
        }
    }

    
    public function update()
    {
        $data = $_POST;

        if (empty($data['category_name'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Category name is required"
            ]);
            return;
        }

        if (empty($data['category_code'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid category"
            ]);
            return;
        }

        //  FIX: use checkCategoryForUpdate
        if ($this->category->checkCategoryForUpdate($data)) {
            echo json_encode([
                "status" => "error",
                "message" => "Category Name Already Added"
            ]);
            return;
        }

        if ($this->category->update($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Category updated successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to update category"
            ]);
        }
    }

  
    public function delete()
    {
        $data = $_POST;

        if (empty($data['category_code'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Invalid category"
            ]);
            return;
        }

        if ($this->category->delete($data)) {
            echo json_encode([
                "status" => "success",
                "message" => "Category deleted successfully"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Failed to delete category"
            ]);
        }
    }
}

?>