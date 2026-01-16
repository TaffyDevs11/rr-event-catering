<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_header.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

$bookings = $pdo->query("
    SELECT id, full_name, event_date, guests, status
    FROM bookings
    ORDER BY created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Bookings</h1>

<table width="100%" cellpadding="10">
<tr>
    <th>Client</th>
    <th>Date</th>
    <th>Guests</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php foreach ($bookings as $b): ?>
<tr>
    <td><?= htmlspecialchars($b['full_name']) ?></td>
    <td><?= htmlspecialchars($b['event_date']) ?></td>
    <td><?= (int)$b['guests'] ?></td>
    <td><?= htmlspecialchars($b['status']) ?></td>
    <td>
        <a href="booking_view.php?id=<?= (int)$b['id'] ?>">View / Edit</a>
    </td>
</tr>
<?php endforeach; ?>
</table>

<?php require_once __DIR__ . '/../includes/admin_footer.php'; ?>
