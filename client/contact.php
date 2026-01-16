<?php 
session_start();

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/client_header.php';

/* Must be logged in */
if (!isset($_SESSION['client_id'])) {
    header("Location: " . SITE_URL . "/client/login.php");
    exit;
}

$client_name  = htmlspecialchars($_SESSION['client_name'] ?? '');
$client_email = htmlspecialchars($_SESSION['client_email'] ?? '');
?>

<section class="hero" style="background-image:url('<?= SITE_URL ?>/assets/images/contact-hero.jpg')">
    <div class="hero-content">
        <h1>Contact Us</h1>
        <p>Let’s plan your perfect event</p>
    </div>
</section>

<section class="section">
    <div class="social-grid">

        <div class="social-card">
            <a href="https://facebook.com" target="_blank">
                <h1>@RRCatering</h1>
                <p>Facebook</p>
            </a>
        </div>

        <div class="social-card">
            <a href="https://instagram.com" target="_blank">
                <h1>@RRCatering</h1>
                <p>Instagram</p>
            </a>
        </div>

        <div class="social-card">
            <a href="https://twitter.com" target="_blank">
                <h1>@RRCatering</h1>
                <p>Twitter / X</p>
            </a>
        </div>

    </div>
</section>
<!-- =========================
     WHY CREATE AN ACCOUNT
========================= -->
<section class="section">
    <div class="section-title">
        <h2 style="color:#D4AF37;">Why Create an Account?</h2>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <h3>Email Address</h3>
            <p>rreventcatering.co.uk</p>
        </div>

        <div class="feature-card">
            <h3>Call/WatsApp number</h3>
            <p>+44 123 456 789</p>
        </div>

        <div class="feature-card">
            <h3>Address</h3>
            <p>Northampton</p>
        </div>
    </div>
</section>
<!-- =========================
     REGULATORY AUTHORITY SECTION
========================= -->
<section class="section" style="background:#0b0b0b; text-align:center" >
    <div class="about-flex">

        <div>
            <h2 style="color:#D4AF37; font-size: 1.5rem;">Highly Rated by UK Regulatory Authorities</h2>
            <p style="margin-top:15px; line-height:1.6;">
                R&amp;R Catering operates under full compliance with UK food safety and
                hygiene regulations. Our processes, hygiene standards, and operational
                practices are independently assessed and rated — giving you total peace
                of mind when booking your event.
            </p>

            <p style="margin-top:15px;">
                <strong>Why this matters:</strong><br>
                • Food safety compliance<br>
                • Professional handling & preparation<br>
                • Trusted by venues and clients across the UK
            </p>
        </div>

        <div style="text-align:center;">
            <img src="<?= SITE_URL ?>/assets/images/rating.jpeg"
                 alt="UK Food Hygiene Rating"
                 style="max-width:260px; border-radius:12px; margin-bottom:10px">

            <br>
            <a href="https://share.google/N05W1RcwBnpa1nT4g"
               target="_blank"
               style="color:#D4AF37; text-decoration:underline;">
                For More Information
            </a>
        </div>

    </div>
</section>

<!-- =========================
     TESTIMONIALS / GOOGLE REVIEWS
========================= -->
<section class="section testimonials">
    <div class="section-title">
        <h2>What Our Clients Say</h2>
        <p style="max-width:700px; margin:15px auto; opacity:0.9;">
            Trusted by clients across the UK for quality, professionalism, and peace of mind.
        </p>
    </div>

    <div class="testimonial-grid">

        <div class="testimonial-card">
            <div class="stars">
                ★★★★★
            </div>
            <p class="testimonial-text">
                “Absolutely outstanding service. The food was fresh, beautifully presented,
                and our guests couldn’t stop talking about it. Highly professional team.”
            </p>
            <h4>— Sarah M.</h4>
        </div>

        <div class="testimonial-card">
            <div class="stars">
                ★★★★★
            </div>
            <p class="testimonial-text">
                “R&amp;R Catering delivered beyond expectations for our corporate event.
                Everything ran smoothly, and compliance standards were clearly top-tier.”
            </p>
            <h4>— James L.</h4>
        </div>

        <div class="testimonial-card">
            <div class="stars">
                ★★★★★
            </div>
            <p class="testimonial-text">
                “From booking to execution, the experience was seamless.
                Professional staff, delicious food, and total peace of mind.”
            </p>
            <h4>— Aisha K.</h4>
        </div>

    </div>

    <div style="text-align:center; margin-top:40px;">
        <a href="https://g.page/r/CX0-EsGVGUXlEBI/review"
           target="_blank"
           class="btn btn-secondary">
            ⭐ Check Out Our Google Reviews
        </a>
    </div>
</section>



<?php require_once __DIR__ . '/includes/footer.php'; ?>