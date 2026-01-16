<?php
// client/verify.php

require_once __DIR__ . '/../config/init.php';

$token = $_GET['token'] ?? '';
$message = '';
$error = '';

if (!$token) {
    $error = "Invalid verification link.";
} else {
    // Check if token exists and is not expired
    $stmt = $pdo->prepare("SELECT id, verify_expires FROM clients WHERE verify_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $error = "Invalid or expired verification token.";
    } else {
        // Check token expiry
        $now = new DateTime();
        $expires = new DateTime($user['verify_expires']);

        if ($now > $expires) {
            $error = "Verification link has expired. Please register again.";
        } else {
            // Activate account
            $update = $pdo->prepare("
                UPDATE clients 
                SET is_active = 1, verify_token = NULL, verify_expires = NULL 
                WHERE id = ?
            ");
            $update->execute([$user['id']]);

            $message = "Your email has been successfully verified! You can now log in.";
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="container" style="padding: 60px 0; max-width: 700px;">
    <h2>Email Verification</h2>

    <?php if ($error): ?>
        <div class="alert alert-danger" style="margin-top:20px;">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="alert alert-success" style="margin-top:20px;">
            <?php echo htmlspecialchars($message); ?>
        </div>

        <a href="<?php echo SITE_URL; ?>/client/login.php" class="btn btn-primary" style="margin-top:20px;">
            Go to Login
        </a>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
