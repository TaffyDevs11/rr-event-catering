<?php
// admin/login.php
session_start();
require_once __DIR__ . '/../config/init.php';

// If already logged in, go to dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: ' . SITE_URL . '/admin/dashboard.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) {
        $errors[] = 'Enter username and password.';
    } else {
        $stmt = $pdo->prepare("SELECT id, password_hash, email FROM admins WHERE username = ? LIMIT 1");
        $stmt->execute([$username]);
        $admin = $stmt->fetch();

        if (!$admin || !password_verify($password, $admin['password_hash'])) {
            $errors[] = 'Invalid credentials.';
        } else {
            // Auth success
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_email'] = $admin['email'];
            header('Location: ' . SITE_URL . '/admin/dashboard.php');
            exit;
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Login — R&R Catering</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/admin.css">
</head>
<body class="admin-body">
  <main class="admin-auth">
    <div class="auth-card">
      <img src="<?php echo SITE_URL; ?>/assets/images/logo.png" alt="R&R Catering" class="auth-logo">
      <h2>Admin Login</h2>

      <?php if ($errors): ?>
        <div class="alert alert-danger">
          <ul><?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?></ul>
        </div>
      <?php endif; ?>

      <form method="post" class="auth-form">
        <label>Username
          <input name="username" type="text" required>
        </label>
        <label>Password
          <input name="password" type="password" required>
        </label>
        <div class="auth-actions">
          <button class="btn btn-gold" type="submit">Sign in</button>
        </div>
      </form>

      <p class="muted">Use the admin account created with setup_admin.php</p>
    </div>
  </main>
</body>
</html>
