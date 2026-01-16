<?php
session_start();

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/mailer.php';
require_once __DIR__ . '/../includes/whatsapp.php';
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();

/* ===============================
   Security: client must be logged in
================================ */
if (!isset($_SESSION['client_id'])) {
    header("Location: " . SITE_URL . "/client/login.php");
    exit;
}

$client_id = (int)$_SESSION['client_id'];

/* ===============================
   Collect POST data
================================ */
$full_name     = trim($_POST['full_name'] ?? '');
$email         = trim($_POST['email'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$event_date    = $_POST['event_date'] ?? '';
$guests        = (int)($_POST['guests'] ?? 0);
$event_address = trim($_POST['event_address'] ?? '');
$notes         = trim($_POST['notes'] ?? '');
$menu          = $_POST['menu'] ?? [];

if (!$full_name || !$email || !$event_date || empty($menu)) {
    die('Invalid booking submission.');
}

/* ===============================
   Encode selected menu
================================ */
$services_json = json_encode($menu, JSON_UNESCAPED_UNICODE);

// ===============================
// CALCULATE TOTAL AMOUNT
// ===============================
$services = $menu; // Fixed, use menu array from POST
$food_total = 0;

if (is_array($services)) {
    $item_ids = [];

    foreach ($services as $group) {
        if (is_array($group)) {
            foreach ($group as $id) {
                $item_ids[] = (int)$id;
            }
        }
    }

    if (!empty($item_ids)) {
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));

        $stmt = $pdo->prepare("
            SELECT IFNULL(SUM(base_price),0)
            FROM food_items
            WHERE id IN ($placeholders)
        ");
        $stmt->execute($item_ids);
        $food_total = (float)$stmt->fetchColumn();
    }
}

// Fixed transport fee
$transport_fee = 120.00;

// FINAL TOTAL
$total_amount = $food_total + $transport_fee;

/* ===============================
   Insert booking
================================ */
$stmt = $pdo->prepare("
INSERT INTO bookings (
    client_id,
    full_name,
    email,
    phone,
    event_date,
    guests,
    event_address,
    services_json,
    status,
    is_paid,
    total_amount,
    created_at
)
VALUES (
    ?,?,?,?,?,?,?,?,
    'pending',0,?,NOW()
)
");

$stmt->execute([
    $client_id,
    $full_name,
    $email,
    $phone,
    $event_date,
    $guests,
    $event_address,
    $services_json,
    $total_amount
]);

$booking_id = $pdo->lastInsertId();


// Fetch booking for notification
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

// Fetch menu items
$menuStmt = $pdo->prepare("
    SELECT m.item_name AS name, m.base_price AS price
    FROM booking_items bi
    JOIN food_items m ON bi.menu_item_id = m.id
    WHERE bi.booking_id = ?
");
$menuStmt->execute([$booking_id]);
$menuItems = $menuStmt->fetchAll(PDO::FETCH_ASSOC);

// Send notifications (FAIL SAFE)
sendAdminBookingEmail($booking, $menuItems);
sendAdminWhatsApp($booking);
/* ===============================
   Admin Email Notification
================================ */
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

try {
    $mail = new PHPMailer(true);
    $mail->isMail(); // Local mail (safe for localhost & hosting)

    $mail->setFrom(ADMIN_EMAIL, 'R&R Catering System');
    $mail->addAddress(ADMIN_EMAIL);

    $mail->isHTML(true);
    $mail->Subject = "🆕 New Booking Received (#{$booking_id})";

    $mail->Body = "
        <h2>New Booking Received</h2>
        <p><strong>Client:</strong> {$full_name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Event Date:</strong> {$event_date}</p>
        <p><strong>Guests:</strong> {$guests}</p>

        <p>
            <a href='" . SITE_URL . "/admin/booking_view.php?id={$booking_id}'
               style='padding:10px 16px;background:#FFD700;color:#000;text-decoration:none;border-radius:6px;'>
               View Booking
            </a>
        </p>
    ";

    $mail->send();

} catch (Exception $e) {
    // Do not block booking if mail fails
}

/* ===============================
   Redirect client
================================ */
header("Location: " . SITE_URL . "/client/dashboard.php?booking=success");
exit;
