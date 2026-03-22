<?php

if (isset($_SESSION['user_id'])) {

    switch ($_SESSION['role']) {
        case 'Admin':
            header("Location: dashboard.php");
            exit;

        case 'Staff':
            header("Location: ../../frontend/view/testing.fuel.php");
            exit;

        case 'Cashier':
            header("Location: cashier/cashier.php");
            exit;

        default:
        header("Location: index.php");
        exit;
    }
}
?>
