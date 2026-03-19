
<?php

require_once __DIR__ . '/../../backend/models/categoryModel.php';

class categoryController
{
    private $user;

    public function __construct($user)
    {
        $this->user = new categoryModel($user);
    }
    
    public function create()
    {
        $data = $_POST;

        if($this->user->checkCategory($data)){
                echo json_encode([
                "status" => "error",
                "message" => "Category Name Already Exist"
            ]);
            return;
        }else{
            if ($this->user->create($data)) {

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
    }
        
    
}



?>