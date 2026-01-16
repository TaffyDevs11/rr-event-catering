<?php
// admin/clients.php
session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/admin_header.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . SITE_URL . '/admin/login.php'); exit; }

$stmt = $pdo->query("SELECT id, full_name, email, phone, is_active, created_at FROM clients ORDER BY created_at DESC LIMIT 200");
$clients = $stmt->fetchAll();

?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
<main class="admin-main container">


  <section class="admin-content">
    <h1>Clients</h1>

    <table class="admin-table">
      <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Active</th><th>Joined</th><th>Action</th></tr></thead>
      <tbody>
      <?php foreach ($clients as $c): ?>
        <tr>
          <td><?php echo htmlspecialchars($c['full_name']); ?></td>
          <td><?php echo htmlspecialchars($c['email']); ?></td>
          <td><?php echo htmlspecialchars($c['phone']); ?></td>
          <td><?php echo $c['is_active'] ? 'Yes' : 'No'; ?></td>
          <td><?php echo htmlspecialchars($c['created_at']); ?></td>
          <td>
            <a class="btn btn-sm" href="booking_view.php?id=<?php echo (int)$c['id']; ?>">View bookings</a>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>

  </section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

