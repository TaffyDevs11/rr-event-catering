<?php
// admin/booking_update.php
session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendPaymentReceiptEmail($booking, $menuItems)
{
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'taffythedev@gmail.com';
        $mail->Password   = 'ijym fzfa ojra ttmw';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('taffythedev@gmail.com', 'R&R Catering');
        $mail->addAddress($booking['email'], $booking['full_name']);

        $mail->isHTML(true);
        $mail->Subject = "✅ Payment Received – Receipt (#{$booking['id']})";

        // Build menu list
        $itemsHtml = '';
        foreach ($menuItems as $item) {
            $itemsHtml .= "
                <tr>
                    <td>{$item['item_name']}</td>
                    <td>£" . number_format($item['price'], 2) . "</td>
                </tr>
            ";
        }

        $mail->Body = "
            <h2>Payment Confirmation</h2>

            <p>Dear <strong>{$booking['full_name']}</strong>,</p>

            <p>We confirm that your payment has been <strong>successfully received</strong>.</p>

            <h3>Booking Details</h3>
            <p>
                <strong>Booking ID:</strong> {$booking['id']}<br>
                <strong>Event Date:</strong> {$booking['event_date']}<br>
                <strong>Venue:</strong> {$booking['event_address']}
            </p>

            <h3>Order Summary</h3>
            <table border='1' cellpadding='8' cellspacing='0' width='100%'>
                <tr>
                    <th align='left'>Item</th>
                    <th align='right'>Price</th>
                </tr>
                {$itemsHtml}
                <tr>
                    <td><strong>Transport Fee</strong></td>
                    <td align='right'>£120.00</td>
                </tr>
                <tr>
                    <td><strong>Total Paid</strong></td>
                    <td align='right'><strong>£" . number_format($booking['total_amount'], 2) . "</strong></td>
                </tr>
            </table>

            <p style='margin-top:20px;'>
                Thank you for choosing <strong>R&R Catering</strong>.
            </p>

            <p>
                If you have any questions, reply to this email or contact us directly.
            </p>

            <p>
                Kind regards,<br>
                <strong>R&R Catering Team</strong>
            </p>
        ";

        $mail->send();
    } catch (Exception $e) {
        // Do NOT block payment flow if email fails
    }
}

if (!isset($_SESSION['admin_id'])) die('Unauthorized');

$booking_id = $_POST['booking_id'] ?? null;
$status = $_POST['status'] ?? null;

if (isset($_POST['toggle_paid'])) {

    // Mark booking as PAID
    $stmt = $pdo->prepare("UPDATE bookings SET is_paid = 1 WHERE id = ?");
    $stmt->execute([$booking_id]);

    // Fetch booking details
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);

    // Fetch menu items
    $menuStmt = $pdo->prepare("
        SELECT item_name, price
        FROM booking_items
        WHERE booking_id = ?
    ");
    $menuStmt->execute([$booking_id]);
    $menuItems = $menuStmt->fetchAll(PDO::FETCH_ASSOC);

    // Send receipt email
    sendPaymentReceiptEmail($booking, $menuItems);
}

if (!$booking_id || !$status) die('Error: Missing required fields.');

$allowed = ['pending','confirmed','cancelled'];
if (!in_array($status, $allowed)) die('Invalid status.');

$stmt = $pdo->prepare("UPDATE bookings SET status=? WHERE id=?");
$updated = $stmt->execute([$status,$booking_id]);

if ($updated) {
    header("Location: booking_view.php?id=$booking_id&msg=updated");
    exit;
} else {
    die('Error updating booking.');
}
