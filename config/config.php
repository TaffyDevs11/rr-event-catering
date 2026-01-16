<?php
// config/config.php

define('SITE_NAME', 'R&R Catering');
define('SITE_URL', 'http://localhost:8080/RRCatering');

// ---------------- ADMIN ----------------
define('ADMIN_EMAIL', 'taffythedev@gmail.com');
define('ADMIN_WHATSAPP', '48782778223');

// ---------------- TIMEZONE ----------------
date_default_timezone_set('Europe/Warsaw');

// ---------------- SMTP (GMAIL APP PASSWORD) ----------------
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'taffythedev@gmail.com');
define('SMTP_PASS', 'ijym fzfa ojra ttmw'); // 👈 REQUIRED
define('SMTP_FROM_NAME', 'R&R Catering Bookings');

// ---------------- SECURITY ----------------
define('SESSION_NAME', 'rrc_sess');
define('COOKIE_LIFETIME', 60 * 60 * 24 * 7);