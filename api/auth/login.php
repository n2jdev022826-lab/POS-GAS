<?php
session_start();
header("Content-Type: application/json");

require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();

try {

    // ✅ ONLY check request method (FIXED)
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid request method"
        ]);
        exit;
    }

    // ✅ Read JSON input (CORRECT WAY)
    $input = json_decode(file_get_contents("php://input"), true);

    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    // ✅ Validate inputs
    if (empty($username) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "All fields are required"
        ]);
        exit;
    }

    // ✅ Prepare query
    $sql = "SELECT * FROM users WHERE username = ? AND is_deleted = 0 LIMIT 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Database prepare failed: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    // ✅ User not found
    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Username or Password"
        ]);
        exit;
    }

    $user = $result->fetch_assoc();

    // ✅ Password verification
    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Username or Password"
        ]);
        exit;
    }

    // ✅ Secure session
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['fname'] = $user['fname'];
    $_SESSION['lname'] = $user['lname'];
    $_SESSION['role'] = $user['role'];

    // ✅ Log login (safe optional)
    $logSql = "INSERT INTO logs (action, commit) VALUES (?, ?)";
    $logStmt = $conn->prepare($logSql);

    if ($logStmt) {
        $action = "Login";
        $commit = (string)$_SESSION['user_id'];
        $logStmt->bind_param("ss", $action, $commit);
        $logStmt->execute();
        $logStmt->close();
    }

    // ✅ Role-based redirect
    switch ($user['role']) {
        case 'admin':
            $redirect = "/POS-GAS/frontend/view/dashboard.php";
            break;
        case 'staff':
            $redirect = "/POS-GAS/frontend/view/testing.fuel.php";
            break;
        case 'cashier':
            $redirect = "/POS-GAS/frontend/view/cashier/cashier.php";
            break;
        default:
            $redirect = "/POS-GAS/frontend/view/index.php";
    }

    // ✅ Success response
    echo json_encode([
        "status" => "success",
        "message" => "Welcome back, " . $user['username'] . "!",
        "redirect" => $redirect
    ]);

} catch (Exception $e) {

    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}