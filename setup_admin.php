<?php
require_once __DIR__ . '/config/database.php';

$username = "superadmin";
$password = "Admin123!";
$email = "your-email@example.com";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admins (username, password_hash, email) VALUES (?, ?, ?)");
$stmt->execute([$username, $hash, $email]);

echo "Admin user created successfully.<br>";
echo "Username: $username<br>Password: $password";
