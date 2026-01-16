<?php
// admin/export_booking.php

require_once __DIR__ . '/../config/init.php';

if (!isset($_SESSION['admin_id'])) {
    exit('Unauthorized');
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) exit('Invalid booking');

/* ===============================
   Fetch booking
================================ */
$stmt = $pdo->prepare("
    SELECT 
        b.event_date,
        b.event_address,
        b.services_json,
        c.full_name
    FROM bookings b
    LEFT JOIN clients c ON b.client_id = c.id
    WHERE b.id = ?
");
$stmt->execute([$id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) exit('Not found');

/* ===============================
   Resolve menu items
================================ */
$menuItems = [];
$menu_raw = json_decode($booking['services_json'], true);

if (is_array($menu_raw)) {
    $ids = [];
    foreach ($menu_raw as $cat) {
        if (is_array($cat)) {
            foreach ($cat as $i) $ids[] = (int)$i;
        }
    }

    if ($ids) {
        $ph = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $pdo->prepare("SELECT item_name FROM food_items WHERE id IN ($ph)");
        $stmt->execute($ids);
        $menuItems = $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

/* ===============================
   CSV OUTPUT (Excel-ready)
================================ */
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename=booking_'.$id.'.csv');

$out = fopen('php://output', 'w');

fputcsv($out, ['Client Name', 'Event Date', 'Venue', 'Menu Items']);
fputcsv($out, [
    $booking['full_name'],
    $booking['event_date'],
    $booking['event_address'],
    implode(' | ', $menuItems)
]);

fclose($out);
exit;