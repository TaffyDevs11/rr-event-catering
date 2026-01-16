<?php
// admin/menu_delete.php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';

$menu_id = (int)($_GET['menu_id'] ?? 0);
if ($menu_id) {
    $stmt = $pdo->prepare("DELETE FROM menu WHERE id=?");
    $stmt->execute([$menu_id]);
}

header('Location: ' . SITE_URL . '/admin/menu.php');
exit;
