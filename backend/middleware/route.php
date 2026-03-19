<?php

if (isset($_SESSION['user_id'])) {

    switch ($_SESSION['role']) {
        case 'admin':
            header("Location: dashboard.php");
            exit;

        case 'staff':
            header("Location: sales.php");
            exit;

        case 'cashier':
            header("Location: cashier/cashier.php");
            exit;

        default:
        header("Location: index.php");
        exit;
    }
}
?>