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

<section class="hero" style="height:100vh;background-image:url('<?= SITE_URL ?>/assets/images/5.webp')">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>Our Gallery</h1>
    <p>Crafted Moments • Exceptional Taste</p>
  </div>
</section>

<section class="section">
  <div class="gallery-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:15px;">
    <?php for($i=1;$i<=32;$i++): ?>
      <img src="<?=SITE_URL?>/assets/images/<?= $i ?>.webp"
           style="width:100%;border-radius:12px;cursor:pointer"
           onclick="openLightbox(this.src)">
    <?php endfor; ?>
  </div>
</section>

<div id="lightbox" onclick="this.style.display='none'"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.9);align-items:center;justify-content:center;">
  <img id="lightboxImg" style="max-width:90%;">
</div>

<script>
function openLightbox(src){
  document.getElementById('lightbox').style.display='flex';
  document.getElementById('lightboxImg').src=src;
}
</script>

<section class="section">
  <h2 style="text-align:center;">Contact Us</h2>
  <form method="post" action="<?=SITE_URL?>/send_mail.php" style="max-width:600px;margin:auto;">
    <input name="name" placeholder="Name">
    <input name="email" type="email" placeholder="Email">
    <textarea name="message" placeholder="Message"></textarea>
    <button>Send</button>
  </form>
</section>

<?php require_once __DIR__.'/../includes/footer.php'; ?>