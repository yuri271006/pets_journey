<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

$admin_password = "caibong";

function check_login() {
    return (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true);
}

if (isset($_POST['login'])) {
    if (($_POST['password'] ?? '') === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: index.php");
        exit;
    } else {
        $error = "Mật khẩu không chính xác.";
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit;
}
?>