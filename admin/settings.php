<?php
// admin/settings.php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
if (!isset($_SESSION['admin_id'])) { header('Location: ' . SITE_URL . '/admin/login.php'); exit; }

// simple settings loading/saving (extend as needed)
$settings = $pdo->query("SELECT skey, svalue FROM settings")->fetchAll(PDO::FETCH_KEY_PAIR);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Example: save admin email
    $admin_email = trim($_POST['admin_email'] ?? '');
    $stmt = $pdo->prepare("UPDATE settings SET svalue = ? WHERE skey = 'admin_email'");
    $stmt->execute([$admin_email]);
    $saved = true;
    $settings['admin_email'] = $admin_email;
}

require_once __DIR__ . '/../includes/header.php';
?>
<link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
<main class="admin-main container">
  <aside class="admin-sidebar">
    <div class="logo-wrap"><img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="logo"></div>
    <nav>
      <a href="dashboard.php">Dashboard</a>
      <a href="bookings.php">Bookings</a>
      <a href="clients.php">Clients</a>
      <a href="settings.php" class="active">Settings</a>
    </nav>
  </aside>

  <section class="admin-content">
    <h1>Settings</h1>
    <?php if (!empty($saved)): ?><div class="alert alert-success">Saved</div><?php endif; ?>

    <form method="post" style="max-width:600px;">
      <label>Admin email
        <input name="admin_email" value="<?php echo htmlspecialchars($settings['admin_email'] ?? ''); ?>">
      </label>

      <div style="margin-top:12px;">
        <button class="btn btn-gold" type="submit">Save</button>
      </div>
    </form>

    <hr>
    <h3>Twilio / SMTP</h3>
    <p>Use settings table (skey 'twilio_sid','twilio_token','twilio_from','smtp_host','smtp_user' etc.). Edit in db or extend UI here.</p>
  </section>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
