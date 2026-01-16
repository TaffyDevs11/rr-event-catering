<?php
require_once __DIR__ . '/../config/config.php';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= htmlspecialchars(SITE_NAME) ?></title>
    <link href="assets/images/logo.png" rel="icon" />
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css?v=<?= time() ?>">
</head>
<body>

<header class="rrc-header">
    <div class="container header-inner">
        <a href="<?= SITE_URL ?>/index.php">
            <img src="<?= SITE_URL ?>/assets/images/logo.png" class="logo-img">
        </a>

        <nav class="main-nav">
            <a href="<?= SITE_URL ?>/index.php" class="holo-link"><span>Home</span></a>
            <a href="<?= SITE_URL ?>/about.php" class="holo-link"><span>About</span></a>
            <a href="<?= SITE_URL ?>/menu.php" class="holo-link"><span>Menu</span></a>
            <a href="<?= SITE_URL ?>/contact.php" class="holo-link"><span>Contact</span></a>

            <a href="<?= SITE_URL ?>/client/login.php" class="btn btn-secondary">Login</a>
            <a href="<?= SITE_URL ?>/client/register.php" class="btn btn-primary">Register</a>
        </nav>
    </div>
</header>

<main>