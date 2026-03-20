<?php

require_once __DIR__ . '/../../backend/models/userModel.php';

class UserController
{

    private $user;

    public function __construct($db) 
    {
        $this->user = new UserModel($db);
    }

    public function create()
    {

        $data = $_POST;

        if($this->user->checkUsername($data)){
                         echo json_encode([
            "status" => "error",
            "message" => "Username already taken"
        ]);
        return;
        }else if($this->user->checkEmail($data)){
            echo json_encode([
                "status" => "error",
                "message" => "Email number already taken"
            ]);
            return;
        }
            else if($this->user->checkPhone($data)){
                echo json_encode([
                    "status" => "error",
                    "message" => "Phone number already taken"
                ]);
                return;
            }
        
        else{
                                                                
         if ($this->user->create($data)) {

            echo json_encode([
                "status" => "success",
                "message" => "User added successfully"
            ]);
        } else {

            echo json_encode([
                "status" => "error",
                "message" => "Failed to add user"
            ]);
        }
        }
    }
}
