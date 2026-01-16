<?php
// includes/mailer.php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

function sendAdminBookingEmail(array $booking, array $menuItems): bool
{
    try {
        $mail = new PHPMailer(true);

        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_USER, SMTP_FROM_NAME);
        $mail->addAddress(ADMIN_EMAIL);

        $mail->isHTML(true);
        $mail->Subject = '📢 New Catering Booking Received';

        $menuHtml = '';
        foreach ($menuItems as $item) {
            $menuHtml .= "<li>{$item['name']} — £" . number_format($item['price'], 2) . "</li>";
        }

        $mail->Body = "
            <h2>New Booking Received</h2>
            <p><strong>Client:</strong> {$booking['full_name']}</p>
            <p><strong>Email:</strong> {$booking['email']}</p>
            <p><strong>Phone:</strong> {$booking['phone']}</p>
            <p><strong>Date:</strong> {$booking['event_date']}</p>
            <p><strong>Guests:</strong> {$booking['guests']}</p>
            <p><strong>Total:</strong> £" . number_format($booking['total_price'], 2) . "</p>

            <h3>Menu Selected</h3>
            <ul>{$menuHtml}</ul>

            <p>
                <a href='" . SITE_URL . "/admin/booking_view.php?id={$booking['id']}'>
                    View Booking in Admin
                </a>
            </p>
        ";

        return $mail->send();
    } catch (Exception $e) {
        error_log('EMAIL ERROR: ' . $e->getMessage());
        return false;
    }
}