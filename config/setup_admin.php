<?php
// setup_admin.php
// RUN THIS ONCE from browser or CLI to create the first admin user.
// After creating, DELETE or move this file outside webroot for security.

// Usage (browser): http://localhost/RRCatering/database/setup_admin.php
// Usage (CLI): php setup_admin.php

require_once __DIR__ . '/../config/database.php';

// change these values BEFORE running
$new_username = 'admin';
$new_password = 'ChangeMe123!'; // CHANGE this to a strong password before running
$new_email = 'owner@rrcatering.co.uk';

// check if admin exists
$stmt = $pdo->prepare("SELECT id FROM admins WHERE username = ?");
$stmt->execute([$new_username]);
if ($stmt->fetch()) {
    echo "Admin user '{$new_username}' already exists. Aborting.\n";
    exit;
}

// hash the password (use PASSWORD_DEFAULT)
$hash = password_hash($new_password, PASSWORD_DEFAULT);

// insert
$ins = $pdo->prepare("INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)");
$ins->execute([$new_username, $hash, $new_email]);

echo "Admin user '{$new_username}' created. PLEASE delete database/setup_admin.php now for security.\n";
