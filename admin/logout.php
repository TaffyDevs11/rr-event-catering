<?php
// admin/logout.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load config so SITE_URL exists
require_once __DIR__ . '/../config/config.php';

// Unset all admin session variables
unset($_SESSION['admin_id']);
unset($_SESSION['admin_username']);

// Optional: destroy session completely
session_destroy();

// Redirect to admin login
header('Location: ' . SITE_URL . '/admin/login.php');
exit;
