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

    $data['created_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

    if($this->user->checkUsername($data)){
        echo json_encode([
            "status" => "error",
            "message" => "Username already taken"
        ]);
        return;
    } 
    else if($this->user->checkEmail($data)){
        echo json_encode([
            "status" => "error",
            "message" => "Email already taken"
        ]);
        return;
    }
    else if($this->user->checkPhone($data)){
        echo json_encode([
            "status" => "error",
            "message" => "Phone already taken"
        ]);
        return;
    }

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

    public function update()
{
    $data = $_POST;

    $data['updated_by'] = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

    if ($this->user->update($data)) {
        echo json_encode([
            "status" => "success",
            "message" => "User updated successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update user"
        ]);
    }
}

public function delete($data)
{
    if (!isset($data['user_code'])) {
        echo json_encode([
            "status" => "error",
            "message" => "User code is required"
        ]);
        return;
    }

    // current logged in user
    $deleted_by = $_SESSION['fname'] . ' ' . $_SESSION['lname'];

    if ($this->user->delete($data['user_code'], $deleted_by)) {

        echo json_encode([
            "status" => "success",
            "message" => "User deleted successfully"
        ]);

    } else {

        echo json_encode([
            "status" => "error",
            "message" => "Failed to delete user"
        ]);
    }
}
}
