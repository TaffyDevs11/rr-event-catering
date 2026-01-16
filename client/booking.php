<?php
// booking.php (multi-step wizard UI)
// Path: C:\xampp\htdocs\RRCatering\client\booking.php

session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/client_header.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

// Must be logged in
if (!isset($_SESSION['client_id'])) {
    header("Location: " . SITE_URL . "/client/login.php");
    exit;
}

$client_id = (int)$_SESSION['client_id'];
$client_name = htmlspecialchars($_SESSION['client_name'] ?? '');
$client_email = htmlspecialchars($_SESSION['client_email'] ?? '');



// Load categories and items
$cat_stmt = $pdo->query("SELECT * FROM food_categories ORDER BY id ASC");
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

$item_stmt = $pdo->query("SELECT * FROM food_items ORDER BY category_id ASC, item_name ASC");
$items_raw = $item_stmt->fetchAll(PDO::FETCH_ASSOC);

$items_by_category = [];
foreach ($items_raw as $item) {
    $items_by_category[$item['category_id']][] = $item;
}
?>
<?php require_once __DIR__ . '/../includes/client_header.php'; ?>

<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/booking.css">

<main class="booking-page">
  <div class="wizard-wrap container">

    <div class="wizard-card">
      <header class="wizard-header">
        <h1>Book with R&amp;R Catering</h1>
        <p class="muted">Luxury catering for your event — quick, polished &amp; secure.</p>
      </header>

      <div class="progress-bar" aria-hidden="true">
        <div class="progress-fill" id="wizardProgress" style="width:0%"></div>
      </div>

      <form id="wizardForm" method="POST" action="<?php echo SITE_URL; ?>/client/submit_booking.php" novalidate>
        <!-- STEP 1: Event basics -->
        <section class="step active" data-step="1" aria-label="Event details">
          <h2 class="step-title">1. Event Details</h2>

          <label class="field">
            <span class="label">Full name</span>
            <input name="full_name" id="full_name" type="text" value="<?php echo $client_name; ?>" required>
          </label>

          <label class="field">
            <span class="label">Email</span>
            <input name="email" id="email" type="email" value="<?php echo $client_email; ?>" required>
          </label>

          <label class="field">
            <span class="label">Phone</span>
            <input name="phone" id="phone" type="text" required>
          </label>

          <div class="grid-2">
            <label class="field">
              <span class="label">Event date</span>
              <input name="event_date" id="event_date" type="date" required>
            </label>

            <label class="field">
              <span class="label">Guests</span>
              <input name="guests" id="guests" type="number" min="1" value="50" required>
            </label>
          </div>

          <label class="field">
            <span class="label">Event address</span>
            <input name="event_address" id="event_address" type="text" required>
          </label>

          <div class="step-actions">
            <button type="button" class="btn btn-gold next-btn" data-next="2">Next — Choose Menu</button>
          </div>
        </section>

        <!-- STEP 2: Menu selection (dynamic categories) -->
        <section class="step" data-step="2" aria-label="Menu selection">
          <h2 class="step-title">2. Menu selection</h2>
          <p class="muted">Please choose the required number from each section. Prices for meats & desserts affect total.</p>

          <?php foreach ($categories as $cat): 
              $catId = (int)$cat['id'];
              $max = (int)$cat['max_selection'];
          ?>
            <div class="menu-section" data-category="<?php echo $catId; ?>">
              <div class="menu-header">
                <h3><?php echo htmlspecialchars($cat['category_name']); ?></h3>
                <div class="hint">Choose <strong><?php echo $max; ?></strong></div>
              </div>

              <div class="menu-grid">
                <?php if (!empty($items_by_category[$catId])): foreach ($items_by_category[$catId] as $item): ?>
                  <label class="menu-item">
                    <input 
                      type="checkbox" 
                      name="menu[<?php echo $catId; ?>][]" 
                      value="<?php echo (int)$item['id']; ?>" 
                      data-price="<?php echo number_format($item['base_price'], 2, '.', ''); ?>"
                      data-category="<?php echo $catId; ?>"
                      class="menu-checkbox"
                    >
                    <div class="mi-top">
                      <div class="mi-title"><?php echo htmlspecialchars($item['item_name']); ?></div>
                      <div class="mi-price">£<?php echo number_format($item['base_price'],2); ?></div>
                    </div>
                  </label>
                <?php endforeach; else: ?>
                  <p class="muted">No items in this category.</p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>

          <div class="step-actions">
            <button type="button" class="btn btn-ghost prev-btn" data-prev="1">Back</button>
            <button type="button" class="btn btn-gold next-btn" data-next="3">Next — Review</button>
          </div>
        </section>

        <!-- STEP 3: Review & confirm -->
        <section class="step" data-step="3" aria-label="Review booking">
          <h2 class="step-title">3. Review & Confirm</h2>

          <div class="review-box">
            <h3>Summary</h3>
            <div class="review-row"><strong>Name:</strong> <span id="rvName"></span></div>
            <div class="review-row"><strong>Email:</strong> <span id="rvEmail"></span></div>
            <div class="review-row"><strong>Phone:</strong> <span id="rvPhone"></span></div>
            <div class="review-row"><strong>Date:</strong> <span id="rvDate"></span></div>
            <div class="review-row"><strong>Guests:</strong> <span id="rvGuests"></span></div>
            <div class="review-row"><strong>Address:</strong> <span id="rvAddress"></span></div>

            <h4>Selected Menu</h4>
            <div id="rvMenu"></div>

            <h4>Pricing</h4>
            <div class="review-row"><strong>Food total:</strong> £<span id="rvFoodTotal">0.00</span></div>
            <div class="review-row"><strong>Transport:</strong> £120.00</div>
            <div class="review-row grand"><strong>Grand total:</strong> £<span id="rvGrandTotal">0.00</span></div>
          </div>

          <input type="hidden" name="total_price" id="total_price">
          <!-- selected menu items will be submitted via checkboxes (already part of form) -->

          <label class="field">
            <span class="label">Additional notes (optional)</span>
            <textarea name="notes" id="notes" rows="3"></textarea>
          </label>

          <div class="step-actions">
            <button type="button" class="btn btn-ghost prev-btn" data-prev="2">Back</button>
            <button type="submit" class="btn btn-gold">Confirm & Submit Booking</button>
          </div>
        </section>

        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      </form>

      <footer class="wizard-footer">
        <p>Need help? Email <a href="mailto:<?php echo htmlspecialchars(ADMIN_EMAIL); ?>"><?php echo htmlspecialchars(ADMIN_EMAIL); ?></a> or call +44 1234 567890</p>
      </footer>
    </div>

    <aside class="wizard-visual">
      <div class="visual-card">
        <img src="<?php echo SITE_URL; ?>/assets/images/chef-hero.jpg" alt="Catering hero" />
        <div class="visual-text">
          <h3>Black &amp; Gold service</h3>
          <p>We bring premium flavour and premium service — plates, cutlery and fuel included.</p>
          <div class="price-note">Transport fee: <strong>£120</strong></div>
        </div>
      </div>
    </aside>

  </div>
</main>

<script src="<?php echo SITE_URL; ?>/assets/js/booking.js" defer></script>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
