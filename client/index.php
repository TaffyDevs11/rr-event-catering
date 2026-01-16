<?php
// booking.php (multi-step wizard UI)
// Path: C:\xampp\htdocs\RRCatering\client\booking.php

session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/client_header.php';

// Must be logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: " . SITE_URL . "/client/login.php");
    exit;
}

$client_id = (int)$_SESSION['client_id'];
$client_name = htmlspecialchars($_SESSION['client_name'] ?? '');
$client_email = htmlspecialchars($_SESSION['client_email'] ?? '');

?>

<!-- =========================
     HERO VIDEO SECTION
========================= -->
<section class="hero" style="background:none; padding:0; height:70vh;">
    <video autoplay muted loop playsinline
           style="position:absolute; inset:0; width:100%; height:100%; object-fit:cover;">
        <source src="<?= SITE_URL ?>/assets/images/bgvideo.mp4" type="video/mp4">
    </video>

    <div class="hero-content">
        <h1 style="color:#D4AF37;">Exceptional Event Catering</h1>
        <p>Trusted • Regulated • Highly Rated in the United Kingdom</p>

        <div class="hero-cta" style="margin-top:25px;">
            <a href="<?= SITE_URL ?>/client/booking.php" class="btn btn-primary">
                Book an Event
            </a>
        </div>
    </div>
</section>

<!-- =========================
     TAGLINE / VALUE STATEMENT
========================= -->
<section class="section">
    <div class="section-title">
        <h1 style="color:#D4AF37;">Catering With Integrity, Excellence & Compliance</h1>
        <p style="max-width:800px; margin:20px auto; opacity:0.9;">
            R&amp;R Catering delivers premium on-site catering for weddings, corporate
            functions, private celebrations, and large-scale events — combining culinary
            excellence with strict UK food safety and regulatory compliance.
        </p>
    </div>
</section>

<!-- =========================
     WHY CREATE AN ACCOUNT
========================= -->
<section class="section" style="background-color: #fefefe; width: 100%;">
    <div class="section-title">
        <h2 style="color:#D4AF37;">Why Us!</h2>
    </div>

    <div class="features-grid">
        <div class="feature-card" style="background-color: #0b0b0b;">
            <h3>Faster Bookings</h3>
            <p >Secure your event date quickly with saved details and streamlined booking.</p>
        </div>

        <div class="feature-card" style="background-color: #0b0b0b;">
            <h3>Booking Management</h3>
            <p>View, track, and manage your bookings anytime from your dashboard.</p>
        </div>

        <div class="feature-card" style="background-color: #0b0b0b;">
            <h3>Priority Communication</h3>
            <p>Receive confirmations, updates, and reminders directly.</p>
        </div>
    </div>

    <div style="text-align:center; margin-top:30px;">
        <a href="<?= SITE_URL ?>/client/booking.php" class="btn view-more-button">
            Book Event
        </a>
    </div>
</section>

<!-- =========================
     SERVICES SNAPSHOT
========================= -->
<section class="section" style="background:#000;">
    <div class="section-title">
        <h2 style="color:#D4AF37;">What We Offer</h2>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <h3>On-Site Cooking</h3>
            <p>Freshly prepared meals cooked and finished at your venue.</p>
        </div>

        <div class="feature-card">
            <h3>Professional Service Staff</h3>
            <p>Experienced, uniformed staff ensuring smooth event execution.</p>
        </div>

        <div class="feature-card">
            <h3>Complete Catering Setup</h3>
            <p>Buffet stations, tableware, cutlery, and presentation handled.</p>
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

<!-- =========================
     FINAL CALL TO ACTION
========================= -->
<section class="cta">
    <div class="cta-inner">
        <h3 style="color:#D4AF37;">Secure Your Event With Confidence</h3>
        <p style="font-size: 1.5rem;">
            £400 deposit secures your date. Final payment due 7 days before your event.
        </p>

        <div style="margin-top:20px;">
            <a href="<?= SITE_URL ?>/client/register.php" class="btn btn-primary">
                Get Started
            </a>
            <a href="<?= SITE_URL ?>/client/menu.php" class="btn btn-secondary">
                Explore Our Menu
            </a>
        </div>
    </div>
</section>

<?php require_once __DIR__.'/../includes/footer.php'; ?>