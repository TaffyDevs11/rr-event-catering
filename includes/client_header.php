<?php
// header.php
// Shared header included at top of pages.
// Path: C:\xampp\htdocs\RRCatering\includes\header.php

// Load site config (for SITE_NAME, SITE_URL, etc.)
require_once __DIR__ . '/../config/config.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo htmlspecialchars(SITE_NAME); ?></title>

    <!-- Main stylesheet -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css?v=<?= time() ?>">
    <link href="assets/images/logo.png" rel="icon" />

    <!-- Simple Google Font (optional) -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Meta for simple SEO -->
    <meta name="description" content="R&R Catering - professional catering in Northampton. Book events, view menus, and contact us.">

</head>
<body class="rrc-body">

<header class="rrc-header">
    <div class="container header-inner">
        <div class="brand">
            <!-- Use client's gold-on-black logo in assets/images/logo.png or logo.svg -->
            <a href="<?php echo SITE_URL; ?>/index.php" class="logo-link">
                <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="<?php echo htmlspecialchars(SITE_NAME); ?>" class="logo-img" />
            </a>
        </div>

<nav class="main-nav">

    <a href="<?= SITE_URL ?>/client/index.php" class="holo-link"><span>Home</span></a>
    <a href="<?= SITE_URL ?>/client/about.php" class="holo-link"><span>About</span></a>
    <a href="<?= SITE_URL ?>/client/menu.php" class="holo-link"><span>Menu</span></a>
    <a href="<?= SITE_URL ?>/client/booking.php" class="holo-link"><span>Booking</span></a>
    <a href="<?= SITE_URL ?>/client/gallery.php" class="holo-link"><span>Gallery</span></a>
    <a href="<?= SITE_URL ?>/client/dashboard.php" class="holo-link"><span>Dashboard</span></a>
    <a href="<?= SITE_URL ?>/client/contact.php" class="holo-link"><span>Contact</span></a>

    <?php if (isset($_SESSION['client_id'])): ?>
    <div class="nav-dropdown">
        <a href="#" class="holo-link nav-user">
            <span>Welcome, <?= htmlspecialchars($_SESSION['client_name']) ?></span>
        </a>

        <div class="dropdown-menu">
            <a href="<?= SITE_URL ?>/client/logout.php">Logout</a>
        </div>
    </div>
    <?php else: ?>
        <a href="<?= SITE_URL ?>/client/login.php" class="btn btn-login">Login</a>
        <a href="<?= SITE_URL ?>/client/register.php" class="btn btn-register">Register</a>
    <?php endif; ?>

</nav>

        <button class="nav-toggle" id="navToggle" aria-label="Toggle navigation">☰</button>
    </div>
</header>

<main id="main-content">
