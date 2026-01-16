<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/includes/header.php';
?>

<section class="hero" style="background-image:url('<?= SITE_URL ?>/assets/images/menu-hero.jpg')">
    <div class="hero-content">
        <h1>Our Menu</h1>
        <p>Deliciously crafted for every occasion</p>
    </div>
</section>

<section class="section">
    <div class="menu-grid">

        <?php
        // Example static items (replace with DB later if needed)
        $menu = [
            ["name"=>"Classic Buffet","price"=>"£15 / person","desc"=>"A balanced mix of traditional favorites"],
            ["name"=>"Premium Buffet","price"=>"£25 / person","desc"=>"Luxury dishes with premium ingredients"],
            ["name"=>"Wedding Special","price"=>"£30 / person","desc"=>"Elegant meals for your special day"],
        ];
        foreach ($menu as $item):
        ?>
        <div class="menu-card">
            <h3><?= $item['name'] ?></h3>
            <p class="menu-price"><?= $item['price'] ?></p>
            <p><?= $item['desc'] ?></p>
            <a href="<?= SITE_URL ?>/client/booking.php" class="btn btn-primary">Book Now</a>
        </div>
        <?php endforeach; ?>

    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>