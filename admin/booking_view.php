<?php
// admin/booking_view.php

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/admin_header.php';

/* ===============================
   Validate booking ID
================================ */
$booking_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($booking_id <= 0) {
    echo "<p class='error'>Invalid booking.</p>";
    require_once __DIR__ . '/../includes/admin_footer.php';
    exit;
}

/* ===============================
   Fetch booking + client
================================ */
$stmt = $pdo->prepare("
    SELECT 
        b.*,
        c.full_name,
        c.email,
        c.phone
    FROM bookings b
    LEFT JOIN clients c ON b.client_id = c.id
    WHERE b.id = ?
");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$booking) {
    echo "<p class='error'>Booking not found.</p>";
    require_once __DIR__ . '/../includes/admin_footer.php';
    exit;
}

/* ===============================
   Decode selected menu
================================ */
$menu_raw = json_decode($booking['services_json'], true);
$menu_items = [];
$food_total = 0.00;

if (is_array($menu_raw)) {

    // Flatten item IDs
    $item_ids = [];
    foreach ($menu_raw as $catItems) {
        if (is_array($catItems)) {
            foreach ($catItems as $id) {
                $item_ids[] = (int)$id;
            }
        }
    }

    if (!empty($item_ids)) {
        $placeholders = implode(',', array_fill(0, count($item_ids), '?'));

        $stmt = $pdo->prepare("
            SELECT fi.item_name, fi.base_price, fc.category_name
            FROM food_items fi
            LEFT JOIN food_categories fc ON fi.category_id = fc.id
            WHERE fi.id IN ($placeholders)
        ");
        $stmt->execute($item_ids);
        $menu_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($menu_items as $item) {
            $food_total += (float)$item['base_price'];
        }
    }
}

$transport_fee = 120.00;
$grand_total = $food_total + $transport_fee;
?>

<div class="dashboard">
    <h1>Booking Details</h1>

    <?php if (isset($_GET['updated'])): ?>
        <div class="success">✅ Booking updated successfully</div>
    <?php endif; ?>

    <div class="booking-info">
        <h2 style="color: #d4af37;">Client Details</h2>
        <p><strong>Name:</strong> <?= htmlspecialchars($booking['full_name']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($booking['email']) ?></p>
        <p><strong>Phone:</strong> <?= htmlspecialchars($booking['phone']) ?></p>
        <p><strong>GDPR Consent:</strong>
            <?= $booking['gdpr_consent'] ?
                'Granted on ' . $booking['gdpr_consent_at'] :
                'NOT GRANTED' ?>
        </p>

        <h2 style="color: #d4af37;">Event Details</h2>
        <p><strong>Event Date:</strong> <?= htmlspecialchars($booking['event_date']) ?></p>
        <p><strong>Guests:</strong> <?= (int)$booking['guests'] ?></p>
        <p><strong>Venue:</strong> <?= htmlspecialchars($booking['event_address']) ?></p>
        <p><strong>Payment Status:</strong>
            <?= $booking['is_paid'] ? 'PAID' : 'UNPAID' ?>
        <p><strong>Confirmed/Rejection</strong> <span class="status <?= strtolower($booking['status']) ?>">
                <?= ucfirst($booking['status']) ?>
            </span>
        </p>
    </div>
    <br>
    <div class="card">
        <h2>Selected Menu</h2>

        <?php if (empty($menu_items)): ?>
            <p>No menu items selected.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Item</th>
                        <th>Price (£)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($menu_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['category_name']) ?></td>
                            <td><?= htmlspecialchars($item['item_name']) ?></td>
                            <td>£<?= number_format($item['base_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Pricing Summary</h2>
        <p><strong>Food total:</strong> £<?= number_format($food_total, 2) ?></p>
        <p><strong>Transport:</strong> £<?= number_format($transport_fee, 2) ?></p>
        <p><strong>Grand total:</strong> £<?= number_format($grand_total, 2) ?></p>
    </div>

    <div class="card">
        <h2>Edit Booking</h2>
        <form method="post" action="booking_update.php">
            <input type="hidden" name="booking_id" value="<?= $booking_id ?>">

            <label>Status</label>
            <select name="status">
                <option value="pending" <?= $booking['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="confirmed" <?= $booking['status'] == 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="cancelled" <?= $booking['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <label>Admin Notes</label>
            <textarea name="admin_notes" rows="4"><?= htmlspecialchars($booking['admin_notes'] ?? '') ?></textarea>

            <form method="post" style="margin-top:20px;">
                <input type="hidden" name="toggle_paid" value="1">
            <button type="submit" class="btn" name="payment_toggle">
                <?= $booking['is_paid'] ? 'Mark as Unpaid' : 'Mark as Paid' ?>
            </button>
            </form>

            <button type="submit" class="btn">Save Changes</button>
            <a href="<?= SITE_URL ?>/admin/bookings.php" class="btn btn-outline">Back</a>
            </main>
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        </form>
    </div>

</div>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>

<!-- SINGLE FORM (FIXED) -->
        <form method="post" action="booking_update.php">
            <input type="hidden" name="booking_id" value="<?= $booking_id ?>">
            <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">

            <label>Status</label>
            <select name="status">
                <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <label>Admin Notes</label>
            <textarea name="admin_notes" rows="4"><?= htmlspecialchars($booking['admin_notes'] ?? '') ?></textarea>

            <!-- PAYMENT TOGGLE -->
            <input type="hidden" name="toggle_paid" value="1">
            <button type="submit" class="btn" name="payment_toggle">
                <?= $booking['is_paid'] ? 'Mark as Unpaid' : 'Mark as Paid' ?>
            </button>

            <br><br>

            <button type="submit" class="btn">Save Changes</button>
            <a href="<?= SITE_URL ?>/admin/bookings.php" class="btn btn-outline">Back</a>
        </form>
    </div>
</div>
