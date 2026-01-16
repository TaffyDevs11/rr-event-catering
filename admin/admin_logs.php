<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/admin_header.php';

$logs = $pdo->query("
    SELECT l.*, a.username
    FROM admin_logs l
    JOIN admins a ON a.id=l.admin_id
    ORDER BY l.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Admin Activity Logs</h1>
<table class="table">
<tr><th>Admin</th><th>Action</th><th>Booking</th><th>Date</th></tr>
<?php foreach ($logs as $log): ?>
<tr>
<td><?= htmlspecialchars($log['username']) ?></td>
<td><?= htmlspecialchars($log['action']) ?></td>
<td><?= $log['booking_id'] ?></td>
<td><?= $log['created_at'] ?></td>
</tr>
<?php endforeach; ?>
</table>