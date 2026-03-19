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

        if($this->user->checkUser($data)){
                         echo json_encode([
            "status" => "error",
            "message" => "User with this phone or email already exists"
        ]);
        return;
        }else{
                                                                
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
