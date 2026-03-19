<?php

class UserModel
{

    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function create($data)
    {


        $passwordInput = $data['password'] ?? '';
        $password = password_hash($passwordInput, PASSWORD_DEFAULT);

        $firstname = $data['firstname'] ?? '';
        $middlename = $data['middlename'] ?? '';
        $lastname = $data['lastname'] ?? '';
        $username = $data['username'] ?? '';
        $email = $data['email'] ?? '';
        $role = $data['role'] ?? '';
        $phone = $data['phone'] ?? '';
        $address = $data['address'] ?? '';
        $birthdate = $data['birthdate'] ?? '';
        $hire_date = $data['hire_date'] ?? '';

        $sql = "INSERT INTO users
        (fname,middlename,lname,username,email,role,phone,address,date_of_birth,hire_date,password,created_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssssssssss",
            $firstname,
            $middlename,
            $lastname,
            $username,
            $email,
            $role,
            $phone,
            $address,
            $birthdate,
            $hire_date,
            $password
        );

        return $stmt->execute();
    }


    public function checkUser($data)
    {
        $phone = $data['phone'] ?? '';
        $email = $data['email'] ?? '';

        $sql = "SELECT * FROM users WHERE phone = ? OR email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ss", $phone, $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }
}
