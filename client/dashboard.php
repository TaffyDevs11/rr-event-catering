<?php

session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/client_header.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: ' . SITE_URL . '/client/login.php');
    exit;
}

$client_id = (int) $_SESSION['client_id'];
$client_name = htmlspecialchars($_SESSION['client_name'] ?? 'Client');

$stmt = $pdo->prepare("
    SELECT id, event_date, guests, status
    FROM bookings
    WHERE client_id = ?
    ORDER BY event_date DESC
");
$stmt->execute([$client_id]);
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../includes/client_header.php';
?>

<section class="section">

  <h2>Welcome, <?= $client_name ?></h2>
  <p>Your bookings at a glance</p>

  <?php if (empty($bookings)): ?>
    <div class="card">
      <p>You have no bookings yet.</p>
      <a href="<?= SITE_URL ?>/client/booking.php" class="btn btn-primary">
        Make Your First Booking
      </a>
    </div>
  <?php else: ?>
    <div class="card" style="overflow-x:auto;">
      <table style="width:100%;border-collapse:collapse;">
        <thead>
          <tr style="color:var(--gold);">
            <th>Date</th>
            <th>Guests</th>
            <th>Status</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $b): ?>
            <tr>
              <td><?= htmlspecialchars($b['event_date']) ?></td>
              <td><?= (int)$b['guests'] ?></td>
              <td><?= htmlspecialchars($b['status']) ?></td>
              <td>
                <a class="btn btn-outline"
                   href="<?= SITE_URL ?>/client/booking.php?booking_id=<?= (int)$b['id'] ?>">
                  View
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>

  <div style="margin-top:30px;">
    <a class="btn btn-primary" href="<?= SITE_URL ?>/client/booking.php">New Booking</a>
    <a class="btn btn-outline" href="<?= SITE_URL ?>/client/logout.php">Log Out</a>
  </div>

</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>