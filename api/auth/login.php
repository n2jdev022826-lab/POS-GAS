<?php

session_start();

header("Content-Type: application/json");

require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();



try{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $sql = "SELECT * FROM users WHERE username = ? AND is_deleted = 0 LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid EmployeeID or Password"
        ]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid EmployeeID or Password"
        ]);
        exit;
    }

    // Set session variables
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['fname'] = $user['fname'];
    $_SESSION['lname'] = $user['lname'];
    $_SESSION['role'] = $user['role'];

    // Log the login action
    $logSql = "INSERT INTO logs (action, commit) VALUES (?, ?)";
    $logStmt = $conn->prepare($logSql);
    $action = "Login";
    $commit = $_SESSION['user_id'];
    $logStmt->bind_param("ss", $action, $commit);
    $logStmt->execute();
    $logStmt->close();

    // Role-based redirect
    switch ($user['role']) {
        case 'admin':
            $redirect = "dashboard.php";
            break;
        case 'staff':
            $redirect = "../../frontend/view/testing.fuel.php";
            break;
        case 'cashier':
            $redirect = "cashier/cashier.php";
            break;
        default:
            $redirect = "";
    }

    echo json_encode([
        "status" => "success",
        "message" => "Welcome Back, " . $user['username'] . "!",
        "redirect" => $redirect
    ]);
}
}catch(Exception $e){
    echo json_encode([
        "status" => "error",
        "message" => "An error occurred: " . $e->getMessage()
    ]);
}
