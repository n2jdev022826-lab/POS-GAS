<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    }

class AuthMiddleware
{

    public static function check()
    {

        if (!isset($_SESSION['user_id'])) {

            http_response_code(401);

            echo json_encode([
                "success" => false,
                "message" => "Unauthorized. Please login."
            ]);

            exit;
        }
    }

    public static function allowOnly($roles = [])
    {
        if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {

            $_SESSION['error'] = " You are not authorized to access that page.";

            // Try to go back
            if (!empty($_SERVER['HTTP_REFERER'])) {
                header("Location: " . $_SERVER['HTTP_REFERER']);
            } else {
                // fallback if no referrer
                switch ($_SESSION['role'] ?? '') {
                    case 'Admin':
                    case 'Staff':
                        header("Location: dashboard");
                        break;

                    case 'Cashier':
                        header("Location: cashier/cashier");
                        break;

                    default:
                        header("Location: index");
                        break;
                }
            }

            exit;
        }
    }
}
