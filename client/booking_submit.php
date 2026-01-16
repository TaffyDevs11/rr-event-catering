<?php
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/csrf.php';
verify_csrf();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: booking.php");
    exit;
}


// Sanitize
$full_name = trim($_POST['full_name']);
$email     = trim($_POST['email']);
$phone     = trim($_POST['phone']);
$event_date= $_POST['event_date'];
$guests    = (int)$_POST['guests'];
$address   = trim($_POST['event_address']);
$notes     = trim($_POST['notes']);

// ===============================
// CALCULATE TOTAL AMOUNT
// ===============================
$services = json_decode($_POST['services_json'], true);
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

try {
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
    json_encode($services),
    $total_amount
]);

    // ✅ ADMIN EMAIL NOTIFICATION
    $subject = "New Catering Booking";
    $message = "
New booking received:

Name: $full_name
Email: $email
Phone: $phone
Event Date: $event_date
Guests: $guests
";

    mail(ADMIN_EMAIL, $subject, $message, "From: bookings@rrcatering.co.uk");

    // ✅ CLIENT CONFIRMATION
    header("Location: booking_success.php");
    exit;

} catch (Exception $e) {
    die("Booking failed. Please try again.");
}
