<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email && $password) {

        $stmt = $pdo->prepare("SELECT * FROM clients WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client && password_verify($password, $client['password_hash'])) {

            $_SESSION['client_id']    = $client['id'];
            $_SESSION['client_name']  = $client['full_name'];
            $_SESSION['client_email'] = $client['email'];

            header("Location: " . SITE_URL . "/client/index.php");
            exit;

        } else {
            $error = "Invalid email or password.";
        }

    } else {
        $error = "All fields are required.";
    }
}
session_regenerate_id(true);
?>

<section class="section">
  <div class="container">

    <div style="max-width:420px;margin:0 auto;background:rgba(255,255,255,0.03);padding:36px;border-radius:14px;">

      <h2 style="color:#D4AF37;margin-bottom:8px;text-align:center;">Client Login</h2>
      <p class="lead" style="text-align:center;margin-bottom:24px;">
        Access your bookings and account
      </p>

      <?php if ($error): ?>
        <p style="background:#2a0000;color:#ffbdbd;padding:12px;border-radius:8px;margin-bottom:16px;">
          <?= htmlspecialchars($error) ?>
        </p>
      <?php endif; ?>

      <form method="POST">

        <div style="margin-bottom:14px;">
          <input
            type="email"
            name="email"
            placeholder="Email address"
            required
            style="width:100%;padding:14px;border-radius:28px;border:none;"
          >
        </div>

        <div style="margin-bottom:18px;">
          <input
            type="password"
            name="password"
            placeholder="Password"
            required
            style="width:100%;padding:14px;border-radius:28px;border:none;"
          >
        </div>

        <button class="btn btn-primary" style="width:100%;">
          Login
        </button>
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
      </form>

      <p style="text-align:center;margin-top:20px;font-size:14px;">
        Don’t have an account?
        <a href="<?= SITE_URL ?>/client/register.php" style="color:#D4AF37;">Register here</a>
      </p>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>