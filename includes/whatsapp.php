<?php
// includes/whatsapp.php

function sendAdminWhatsApp(array $booking): bool
{
    if (!defined('TWILIO_SID')) return false;

    $message = "
📢 New Booking!

Client: {$booking['full_name']}
Date: {$booking['event_date']}
Guests: {$booking['guests']}
Total: £" . number_format($booking['total_price'], 2) . "

View:
" . SITE_URL . "/admin/booking_view.php?id={$booking['id']}
";

    $url = "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_SID . "/Messages.json";

    $data = http_build_query([
        'From' => TWILIO_WHATSAPP_FROM,
        'To'   => 'whatsapp:' . ADMIN_WHATSAPP,
        'Body' => $message
    ]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => $data,
        CURLOPT_USERPWD => TWILIO_SID . ':' . TWILIO_TOKEN
    ]);

    $response = curl_exec($ch);
    $error    = curl_error($ch);
    curl_close($ch);

    if ($error) {
        error_log('WHATSAPP ERROR: ' . $error);
        return false;
    }

    return true;
}