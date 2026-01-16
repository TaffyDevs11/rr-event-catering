<?php
session_start();

require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../includes/csrf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $full_name = trim($_POST['full_name'] ?? '');
  $email     = strtolower(trim($_POST['email'] ?? ''));
  $phone     = trim($_POST['phone'] ?? '');
  $password  = $_POST['password'] ?? '';
  $confirm   = $_POST['confirm_password'] ?? '';

  if (!$full_name) $errors[] = 'Full name is required.';
  if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email is required.';
  if (!$phone) $errors[] = 'Phone number is required.';
  if (!$password) $errors[] = 'Password is required.';
  if ($password !== $confirm) $errors[] = 'Passwords do not match.';

  if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT id FROM clients WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) $errors[] = 'An account with this email already exists.';
  }

  if (empty($errors)) {
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $verify_token = bin2hex(random_bytes(32));
    $verify_expires = (new DateTime('+1 hour'))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("
            INSERT INTO clients (full_name, email, phone, password_hash, is_active, verify_token, verify_expires)
            VALUES (?, ?, ?, ?, 0, ?, ?)
        ");
    $stmt->execute([$full_name, $email, $phone, $password_hash, $verify_token, $verify_expires]);

    $success = "Registration successful! Check your email to verify your account.";
  }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="section">
  <div class="container">

    <div style="max-width:520px;margin:0 auto;background:rgba(255,255,255,0.03);padding:36px;border-radius:14px;">

      <h2 style="color:#D4AF37;text-align:center;margin-bottom:8px;">Client Registration</h2>
      <p class="lead" style="text-align:center;margin-bottom:24px;">
        Create your R&R Catering account
      </p>

      <?php if ($errors): ?>
        <div style="background:#2a0000;color:#ffbdbd;padding:14px;border-radius:10px;margin-bottom:18px;">
          <ul style="padding-left:18px;">
            <?php foreach ($errors as $e): ?>
              <li><?= htmlspecialchars($e) ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>

      <?php if ($success): ?>
        <p style="background:#0f2a00;color:#c9ffbd;padding:14px;border-radius:10px;">
          <?= htmlspecialchars($success) ?>
        </p>
      <?php else: ?>

        <form method="POST">

          <?php
          function field($name, $type, $placeholder)
          {
            $val = htmlspecialchars($_POST[$name] ?? '');
            echo "
            <div style='margin-bottom:14px;'>
              <input
                type='{$type}'
                name='{$name}'
                placeholder='{$placeholder}'
                value='{$val}'
                required
                style='width:100%;padding:14px;border-radius:28px;border:none;'
              >
            </div>";
          }

          field('full_name', 'text', 'Your Full name');
          field('email', 'email', 'Your Email address');
          field('phone', 'text', 'Your Whatsapp Phone number');
          ?>

          <div style="margin-bottom:14px;">
            <input type="password" name="password" placeholder="Password" required
              style="width:100%;padding:14px;border-radius:28px;border:none;">
          </div>

          <div style="margin-bottom:18px;">
            <input type="password" name="confirm_password" placeholder="Confirm password" required
              style="width:100%;padding:14px;border-radius:28px;border:none;">
          </div>

          <div class="form-group gdpr-box">
            <label>
              <input type="checkbox" name="gdpr_consent" required>
              I consent to R&R Catering collecting and processing my personal data
              in accordance with the
              <a href="<?= SITE_URL ?>/privacy-policy.php" target="_blank">
                Privacy Policy
              </a>.
            </label>
          </div>

          <button class="btn btn-primary" style="width:100%;">
            Register
          </button>
          <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        </form>

      <?php endif; ?>

      <p style="text-align:center;margin-top:20px;font-size:14px;">
        Already registered?
        <a href="<?= SITE_URL ?>/client/login.php" style="color:#D4AF37;">Login here</a>
      </p>

    </div>
  </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>