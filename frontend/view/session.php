<?php
session_start();

require_once "../../config/database.php"; // Make sure the path is correct
$db = new Database();
$conn = $db->connect();

// Log the logout action BEFORE destroying the session
if (isset($_SESSION['user_id'])) {
    $logSql = "INSERT INTO logs (action, commit) VALUES (?, ?)";
    $logStmt = $conn->prepare($logSql);
    $action = "Logout";
    $commit = $_SESSION['user_id'];
    $logStmt->bind_param("ss", $action, $commit);
    $logStmt->execute();
    $logStmt->close();
}

// Clear all session variables
$_SESSION = [];

// Destroy the session completely
session_destroy();

// Optional: clear the session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Redirect to login page
header("Location: index.php");
exit();
