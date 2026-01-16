<?php
require_once __DIR__ . '/config/database.php';
$stmt = $pdo->query("SELECT NOW() AS now");
$row = $stmt->fetch();
echo 'DB time: ' . $row['now'];
