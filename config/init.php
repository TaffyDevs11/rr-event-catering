<?php
// config/init.php

// -------------------------------
// SESSION
// -------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// -------------------------------
// CONFIG & DATABASE
// -------------------------------
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

// -------------------------------
// ERROR HANDLING
// -------------------------------
// Change to false on live server
$DEBUG_MODE = true;

if ($DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// -------------------------------
// SECURITY HEADERS (SAFE DEFAULTS)
// -------------------------------
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');