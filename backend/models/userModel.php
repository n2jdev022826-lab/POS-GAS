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

    $password = password_hash($data['password'], PASSWORD_DEFAULT);

    $created_by = $data['created_by'] ?? '';

    $sql = "INSERT INTO users
    (fname, middlename, lname, username, sex, email, role, phone, address, date_of_birth, hire_date, password, image, created_by)
    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param(
        "ssssssssssssss",
        $data['firstname'],
        $data['middlename'],
        $data['lastname'],
        $data['username'],
        $data['sex'],
        $data['email'],
        $data['role'],
        $data['phone'],
        $data['address'],
        $data['birthdate'],
        $data['hire_date'],
        $password,
        $imageName,
        $created_by
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

    // STEP 1: Get old image
    $oldImage = '';
    $getSql = "SELECT image FROM users WHERE user_code = ?";
    $getStmt = $this->conn->prepare($getSql);
    $getStmt->bind_param("s", $user_code);
    $getStmt->execute();
    $result = $getStmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $oldImage = $row['image'];
    }

    // STEP 2: Handle image
    $imageName = $oldImage;

    if (isset($_FILES['image']) && $_FILES['image']['name']) {
        $imageName = time() . '_' . $_FILES['image']['name'];
        $target = "../../frontend/assets/uploads/users/" . $imageName;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            return false;
        }
    }

    // STEP 3: Handle password (optional)
    if (!empty($data['password'])) {
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
    } else {
        // keep old password
        $passSql = "SELECT password FROM users WHERE user_code=?";
        $passStmt = $this->conn->prepare($passSql);
        $passStmt->bind_param("s", $user_code);
        $passStmt->execute();
        $passResult = $passStmt->get_result()->fetch_assoc();
        $password = $passResult['password'];
    }

    $updated_by = $data['updated_by'] ?? '';

    // STEP 4: CLEAN SQL (FIXED)
    $sql = "UPDATE users SET 
        fname=?, 
        middlename=?, 
        lname=?, 
        username=?, 
        sex=?, 
        email=?, 
        role=?, 
        phone=?, 
        address=?, 
        date_of_birth=?, 
        hire_date=?, 
        password=?, 
        image=?, 
        updated_by=?
        WHERE user_code=?";

    $stmt = $this->conn->prepare($sql);

    $stmt->bind_param(
        "sssssssssssssss",
        $data['firstname'],
        $data['middlename'],
        $data['lastname'],
        $data['username'],
        $data['sex'],
        $data['email'],
        $data['role'],
        $data['phone'],
        $data['address'],
        $data['birthdate'],   // ✅ FIXED
        $data['hire_date'],
        $password,
        $imageName,
        $updated_by,
        $user_code
    );

    if ($stmt->execute()) {

        // STEP 5: Delete old image if replaced
        if (isset($_FILES['image']) && $_FILES['image']['name'] && $oldImage) {
            $oldPath = "../../frontend/assets/uploads/users/" . $oldImage;

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        return true;
    }

    return false;
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
