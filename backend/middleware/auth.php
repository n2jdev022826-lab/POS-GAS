<?php
session_start();

if(!isset($_SESSION['user_id'])){

    $_SESSION['error'] = "Please login first to access that page.";

    header("Location: index.php");
    exit();
}