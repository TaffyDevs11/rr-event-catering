<?php
// resend_verification.php
// Resend email verification token for unverified users
// Path: C:\xampp\htdocs\RRCatering\client\resend_verification.php

session_start();
require_once __DIR__ . '/../config/init.php';
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;

$errors = [];
$success = '';

// Simple rate limit: allow resend once every 5 minutes per session
$cooldownSeconds = 300; // 5 minutes
$lastResend = $_SESSION['last_resend'] ?? 0;
$now = time();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    } elseif ($now - $lastResend < $cooldownSeconds) {
        $errors[] = 'Please wait before requesting another verification. Try again later.';
    } else {
        // find unverified client
        $stmt = $pdo->prepare("SELECT id, full_name, is_active FROM clients WHERE email = ?");
        $stmt->execute([$email]);
        $client = $stmt->fetch();

        if (!$client) {
            $errors[] = "No account found with that email address.";
        } elseif ((int)$client['is_active'] === 1) {
            $errors[] = "Account already verified. You can log in.";
        } else {
            // create new token and expiry
            $token = bin2hex(random_bytes(32));
            $expires = (new DateTime())->modify('+' . VERIFICATION_TOKEN_EXPIRES_HOURS . ' hours')->format('Y-m-d H:i:s');

            $upd = $pdo->prepare("UPDATE clients SET verify_token = ?, verify_expires = ? WHERE id = ?");
            $ok = $upd->execute([$token, $expires, $client['id']]);

            if ($ok) {
                // send verification email (reuse earlier send code inline to avoid duplication)
                $verifyUrl = SITE_URL . "/client/verify.php?token=" . urlencode($token);
                $subject = SITE_NAME . " - Please verify your email";
                $body = "<p>Hi " . htmlspecialchars($client['full_name']) . ",</p>
                         <p>Please click to verify your email:</p>
                         <p><a href='{$verifyUrl}'>Verify my email</a></p>
                         <p>This link will expire in " . VERIFICATION_TOKEN_EXPIRES_HOURS . " hours.</p>";

                try {
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = SMTP_HOST;
                    $mail->SMTPAuth = true;
                    $mail->Username = SMTP_USER;
                    $mail->Password = SMTP_PASS;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = SMTP_PORT;

                    $mail->setFrom(MAIL_FROM, MAIL_FROM_NAME);
                    $mail->addAddress($email, $client['full_name']);
                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;
                    $mail->AltBody = strip_tags(str_replace("</p>", "\n\n", $body));
                    $mail->send();

                    $success = "Verification email resent. Please check your inbox.";
                    $_SESSION['last_resend'] = $now;
                } catch (Exception $e) {
                    error_log("Resend mail failed: " . $e->getMessage());
                    $errors[] = "Failed to send verification email. Check SMTP settings.";
                }
            } else {
                $errors[] = "Failed to generate verification token. Try again later.";
            }
        }
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="container" style="padding:40px 0;">
    <h2>Resend Verification Email</h2>

    <?php if (!empty($errors)): ?>
        <div class="form-errors"><ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="form-success"><?php echo htmlspecialchars($success); ?></div>
    <?php else: ?>
        <form method="post" class="rrc-form" style="max-width:460px;">
            <label for="email">Enter your account email</label>
            <input id="email" name="email" type="email" required>
            <button type="submit" class="btn btn-primary">Resend verification</button>
        </form>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
