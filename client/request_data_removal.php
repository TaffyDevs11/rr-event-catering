<?php
require_once __DIR__ . '/../config/database.php';

$email = $_POST['email'] ?? '';

$stmt = $pdo->prepare("
    UPDATE bookings 
    SET gdpr_consent = 0 
    WHERE email = ?
");
$stmt->execute([$email]);

echo "Your request has been submitted.";