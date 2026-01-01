<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/GoogleAuthenticator.php';

header('Content-Type: application/json');

if (!isset($_SESSION['2fa_pending_user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Session expired or invalid']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$code = $data['code'] ?? '';

$stmt = $pdo->prepare("SELECT two_factor_secret FROM users WHERE id = ?");
$stmt->execute([$_SESSION['2fa_pending_user_id']]);
$secret = $stmt->fetchColumn();

$ga = new PHPGangsta_GoogleAuthenticator();
if ($ga->verifyCode($secret, $code, 2)) {
    // Success
    $_SESSION['user_id'] = $_SESSION['2fa_pending_user_id'];
    $_SESSION['username'] = $_SESSION['2fa_pending_username'];
    unset($_SESSION['2fa_pending_user_id']);
    unset($_SESSION['2fa_pending_username']);
    
    logActivity($pdo, $_SESSION['user_id'], 'login_2fa', 'Logged in with 2FA');
    
    echo json_encode(['status' => 'success', 'message' => 'Login successful']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid Code']);
}
?>