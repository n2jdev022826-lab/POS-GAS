<?php
session_start();
header("Content-Type: application/json");

require_once "../../config/database.php";

$db = new Database();
$conn = $db->connect();

try {

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid request method"
        ]);
        exit;
    }

    $input = json_decode(file_get_contents("php://input"), true);

    $username = $input['username'] ?? '';
    $password = $input['password'] ?? '';

    if (empty($username) || empty($password)) {
        echo json_encode([
            "status" => "error",
            "message" => "All fields are required"
        ]);
        exit;
    }

    /* ================= GET USER ================= */
    $sql = "SELECT * FROM users WHERE username = ? AND is_deleted = 0 LIMIT 1";
    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Username or Password"
        ]);
        exit;
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid Username or Password"
        ]);
        exit;
    }

    /* ================= SECURE SESSION ================= */
    session_regenerate_id(true);

    $_SESSION['user_id']   = $user['id'];
    $_SESSION['username']  = $user['username'];
    $_SESSION['fname']     = $user['fname'];
    $_SESSION['lname']     = $user['lname'];
    $_SESSION['role']      = $user['role'];
    $_SESSION['image']     = $user['image'];

    /* =======================================================
       ✅ FETCH PERMISSIONS (FIXED VERSION)
    ======================================================= */
    $permSql = "
        SELECT p.permissions_name AS name
        FROM user_permissions up
        JOIN permissions p ON up.permission_id = p.permissions_id
        WHERE up.permission_for = ? 
        AND up.is_allowed = 1
        AND up.is_deleted = 0
        AND p.is_deleted = 0
    ";

    $permStmt = $conn->prepare($permSql);

    $permissions = [];

    if ($permStmt) {

        // If your user.id is VARCHAR → use "s"
        $permStmt->bind_param("s", $user['id']);

        $permStmt->execute();
        $permResult = $permStmt->get_result();

        while ($row = $permResult->fetch_assoc()) {
            $permissions[$row['name']] = true;
        }

        $permStmt->close();
    }

    $_SESSION['user_permissions'] = $permissions;

    /* ================= LOG LOGIN ================= */
    $logSql = "INSERT INTO logs (action, commit) VALUES (?, ?)";
    $logStmt = $conn->prepare($logSql);

    if ($logStmt) {
        $action = "Login";
        $commit = (string)$user['id'];
        $logStmt->bind_param("ss", $action, $commit);
        $logStmt->execute();
        $logStmt->close();
    }

    /* ================= REDIRECT ================= */
    switch ($user['role']) {
        case 'Admin':
            $redirect = "dashboard.php";
            break;
        case 'Staff':
            $redirect = "testing.fuel.php";
            break;
        case 'Cashier':
            $redirect = "cashier/cashier.php";
            break;
        default:
            $redirect = "index.php";
    }

    echo json_encode([
        "status" => "success",
        "message" => "Welcome back, " . $user['username'],
        "redirect" => $redirect
    ]);

} catch (Exception $e) {
    echo json_encode([
        "status" => "error",
        "message" => $e->getMessage()
    ]);
}