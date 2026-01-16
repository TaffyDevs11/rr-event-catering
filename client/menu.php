<?php
session_start();
require_once __DIR__.'/../config/config.php';
require_once __DIR__ . '/../includes/client_header.php';

function renderMenu($items) {
    foreach ($items as $item) {
        $file = strtolower(str_replace(' ', '_', $item));
        ?>
        <div class="card">
            <img src="<?= SITE_URL ?>/assets/images/menu/<?= $file ?>.jpg"
                 alt="<?= htmlspecialchars($item) ?>"
                 style="width:100%;border-radius:12px;">
            <h3><?= htmlspecialchars($item) ?></h3>
            <p>£15</p>

            <?php if (!isset($_SESSION['client_id'])): ?>
                <a href="<?= SITE_URL ?>/client/register.php" class="btn btn-outline">Create Account</a>
            <?php else: ?>
                <a href="<?= SITE_URL ?>/client/booking.php" class="btn btn-primary">Book Now</a>
            <?php endif; ?>
        </div>
        <?php
    }
}
?>

<section class="hero" style="height:100vh;background-image:url('<?= SITE_URL ?>/assets/images/9.webp')">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <h1>Our Menu</h1>
        <p>Luxury Catering Crafted to Perfection</p>
    </div>
</section>

<section class="section" style="background:#111;">
    <p style="max-width:800px;margin:auto;text-align:center;">
        Carefully curated dishes blending tradition, flavour and presentation.
    </p>
</section>

<!-- MEATS -->
<section class="section">
    <h2 style="font-size: 4rem; text-align: center;">Meats</h2>
    <div class="card-grid">
        <?php renderMenu([
            "Beef stew",
            "Oxtail",
            "Fried fish",
            "Pork ribs",
            "Curry goat",
            "Roasted chicken",
            "Lamb stew",
            "Boerewors"
        ]); ?>
    </div>
</section>

<!-- GREENS -->
<section class="section">
    <h2 style="font-size: 4rem; text-align: center;">Greens</h2>
    <div class="card-grid">
        <?php renderMenu([
            "Greens",
            "Sugar beans",
            "Vegetable curry"
        ]); ?>
    </div>
</section>

<!-- STARCHES -->
<section class="section">
    <h2 style="font-size: 4rem; text-align: center;">Starches</h2>
    <div class="card-grid">
        <?php renderMenu([
            "Fried rice",
            "Jollof rice",
            "New potatoes",
            "Sadza",
            "Peanut butter rice",
            "Pasta bake"
        ]); ?>
    </div>
</section>

<!-- SALADS -->
<section class="section">
    <h2 style="font-size: 4rem; text-align: center;">Salads</h2>
    <div class="card-grid">
        <?php renderMenu([
            "Coleslaw",
            "Rocket salad",
            "Greek salad",
            "Potato salad",
            "Waldorf salad"
        ]); ?>
    </div>
</section>

<!-- DESSERTS -->
<section class="section">
    <h2 style="font-size: 4rem; text-align: center;">Desserts</h2>
    <div class="card-grid">
        <?php renderMenu([
            "Fruits",
            "Cup cakes",
            "Triffle",
            "Cheese cakes"
        ]); ?>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>