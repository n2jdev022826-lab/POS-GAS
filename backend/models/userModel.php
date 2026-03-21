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

        $imageName = '';

        if (isset($_FILES['image']) && $_FILES['image']['name']) {
            $imageName = time() . '_' . $_FILES['image']['name'];
            $target = "../../frontend/assets/uploads/users/" . $imageName;

            move_uploaded_file($_FILES['image']['tmp_name'], $target);
        }

        $passwordInput = $data['password'] ?? '';
        $password = password_hash($passwordInput, PASSWORD_DEFAULT);

        $firstname = $data['firstname'] ?? '';
        $middlename = $data['middlename'] ?? '';
        $lastname = $data['lastname'] ?? '';
        $username = $data['username'] ?? '';
        $sex = $data['sex'] ?? '';
        $email = $data['email'] ?? '';
        $role = $data['role'] ?? '';
        $phone = $data['phone'] ?? '';
        $address = $data['address'] ?? '';
        $birthdate = $data['birthdate'] ?? '';
        $hire_date = $data['hire_date'] ?? '';

        $sql = "INSERT INTO users
(fname,middlename,lname,username,sex,email,role,phone,address,date_of_birth,hire_date,password,image,created_at)
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())";

        $stmt = $this->conn->prepare($sql);

        $stmt->bind_param(
            "sssssssssssss",
            $firstname,
            $middlename,
            $lastname,
            $username,
            $sex,
            $email,
            $role,
            $phone,
            $address,
            $birthdate,
            $hire_date,
            $password,
            $imageName
        );

        return $stmt->execute();
    }


    public function checkUsername($data)
    {
        $username = $data['username'] ?? '';


        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function checkPhone($data)
    {
        $phone = $data['phone'] ?? '';


        $sql = "SELECT * FROM users WHERE phone = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }

    public function checkEmail($data)
    {
        $email = $data['email'] ?? '';


        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        return $stmt->num_rows > 0;
    }




    public function update($data)
{
    $user_code = $data['user_code'];

    $imageName = '';

    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $target = "../../frontend/assets/uploads/users/" . $imageName;
        move_uploaded_file($_FILES['image']['tmp_name'], $target);
    }

    $sql = "UPDATE users SET 
        fname=?, middlename=?, lname=?, username=?, sex=?, email=?, role=?, phone=?, address=?, hire_date=?";

    if ($imageName) {
        $sql .= ", image=?";
    }

    $sql .= " WHERE user_code=?";

    $stmt = $this->conn->prepare($sql);

    if ($imageName) {
        $stmt->bind_param(
            "ssssssssssss",
            $data['firstname'],
            $data['middlename'],
            $data['lastname'],
            $data['username'],
            $data['sex'],
            $data['email'],
            $data['role'],
            $data['phone'],
            $data['address'],
            $data['hire_date'],
            $imageName,
            $user_code
        );
    } else {
        $stmt->bind_param(
            "sssssssssss",
            $data['firstname'],
            $data['middlename'],
            $data['lastname'],
            $data['username'],
            $data['sex'],
            $data['email'],
            $data['role'],
            $data['phone'],
            $data['address'],
            $data['hire_date'],
            $user_code
        );
    }

    return $stmt->execute();
}


public function delete($user_code, $deleted_by)
{
    $sql = "UPDATE users 
            SET is_deleted = 1, deleted_by = ? 
            WHERE user_code = ?";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param("ss", $deleted_by, $user_code);

    return $stmt->execute();
}
}
