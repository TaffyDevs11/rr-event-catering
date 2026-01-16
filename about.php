<?php
session_start();

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/header.php';


$client_name  = htmlspecialchars($_SESSION['client_name'] ?? '');
$client_email = htmlspecialchars($_SESSION['client_email'] ?? '');
?>

<!-- HERO -->
<section class="hero" style="height:100vh;background-image:url('<?= SITE_URL ?>/assets/images/15.webp')">
  <div class="hero-overlay"></div>
  <div class="hero-content">
    <h1>About Us</h1>
  </div>
</section>

<!-- FOUNDERS -->
<section class="section">
  <h2 style="text-align:center;">Meet the Founders</h2>

  <div class="card-grid">
    <div class="card">
      <img src="<?= SITE_URL ?>/assets/images/6.webp" alt="Founder One" style="width:120px;border-radius:50%;">
      <h3>Rosina Chipangano</h3>
      <small>Executive Chef</small>
      <p>Passionate about culinary excellence and premium event experiences.</p>
    </div>

    <div class="card">
      <img src="<?= SITE_URL ?>/assets/images/founder2.jpg" alt="Founder Two" style="width:120px;border-radius:50%;">
      <h3>Rumbie Chipangano</h3>
      <small>Operations Director</small>
      <p>Ensuring flawless execution, logistics, and client satisfaction.</p>
    </div>
  </div>
</section>

<!-- VALUES -->
<section class="section" style="background:#111;">
  <h2 style="text-align:center;">Our Values</h2>

  <div class="card-grid">
    <div class="card">
      <div class="icon-circle">★</div>
      <h3>Quality</h3>
      <p>Only premium ingredients.</p>
    </div>

    <div class="card">
      <div class="icon-circle">✓</div>
      <h3>Compliance</h3>
      <p>Strict food safety standards.</p>
    </div>

    <div class="card">
      <div class="icon-circle">❤</div>
      <h3>Passion</h3>
      <p>We love what we do.</p>
    </div>

    <div class="card">
      <div class="icon-circle">⚡</div>
      <h3>Reliability</h3>
      <p>On time, every time.</p>
    </div>
  </div>
</section>

<!-- CONTACT -->
<section class="section">
  <h2 style="text-align:center;">Contact Us</h2>

  <form method="post" action="<?= SITE_URL ?>/send_mail.php" style="max-width:600px;margin:auto;">
    <input name="name" placeholder="Your Name" required>
    <input name="email" type="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Message" required></textarea>
    <button type="submit">Send Message</button>
  </form>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>