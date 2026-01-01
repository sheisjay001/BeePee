<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$email = filter_var($data['email'] ?? '', FILTER_VALIDATE_EMAIL);

if (!$email) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

try {
    // Check if table exists
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'password_resets'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
        $pdo->exec("CREATE TABLE password_resets (
            email VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            KEY(email),
            KEY(token)
        )");
    }

    // Check if user exists (don't reveal this to the user, but we need to know)
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $token = bin2hex(random_bytes(32));
        
        // Delete old tokens
        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        
        // Insert new token
        $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)")->execute([$email, $token]);
        
        // Send Email
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $path = dirname($_SERVER['PHP_SELF']);
        $resetLink = "$protocol://" . $_SERVER['HTTP_HOST'] . $path . "/reset_password_ui.php?token=" . $token . "&email=" . urlencode($email);
        
        $subject = "Reset your BeePee Password";
        $message = "Hi,\n\nClick the link below to reset your password:\n\n" . $resetLink . "\n\nIf you didn't request this, please ignore this email.\n\nThis link will expire in 1 hour.";
        $headers = "From: noreply@beepee.local";

        // Attempt to send email
        // In XAMPP/Localhost without SMTP, this might fail or require configuration.
        // We will log the link for development purposes.
        error_log("Password Reset Link for $email: $resetLink");
        
        @mail($email, $subject, $message, $headers);
    }

    // Always return success to prevent email enumeration
    echo json_encode(['status' => 'success', 'message' => 'If an account exists with this email, a reset link has been sent.']);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
}
?>