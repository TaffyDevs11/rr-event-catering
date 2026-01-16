<?php
require_once __DIR__ . '/../config/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

$admin_username = $_SESSION['admin_username'] ?? 'Administrator';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link href="assets/images/logo.png" rel="icon" />
<title><?= SITE_NAME ?> | Admin</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- Admin CSS -->
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin_style.css?v=<?= time() ?>">
</head>

<body>

<header class="admin-topbar">

    <!-- LEFT: Admin Menu -->
    <nav class="admin-menu">
        <a href="<?= SITE_URL ?>/admin/menu.php">Menu</a>
        <a href="<?= SITE_URL ?>/admin/bookings.php">Bookings</a>
        <a href="<?= SITE_URL ?>/admin/clients.php">Clients</a>
        <a href="<?= SITE_URL ?>/admin/calendar.php">Calendar</a>
        <a href="<?= SITE_URL ?>/admin/dashboard.php">Dashboard</a>
    </nav>

    <!-- CENTER: Brand / Logo -->
    <div class="admin-brand">
        <img src="<?= SITE_URL ?>/assets/images/logo.png" alt="Logo">
        <span><?= SITE_NAME ?> Admin</span>
    </div>

    <!-- RIGHT: Admin User Dropdown -->
    <div class="admin-user">
        <button class="admin-user-btn" onclick="toggleAdminDropdown()">
            Welcome, <strong><?= htmlspecialchars($admin_username) ?></strong>
            <span class="caret">▾</span>
        </button>

        <div class="admin-dropdown" id="adminDropdown">
            <a href="<?= SITE_URL ?>/admin/admin_logs.php">Admin Log</a>
            <a href="<?= SITE_URL ?>/admin/settings.php">Profile / Settings</a>
            <hr>
            <a href="<?= SITE_URL ?>/admin/logout.php" class="logout">Logout</a>
        </div>
    </div>

</header>

<div class="admin-wrapper">