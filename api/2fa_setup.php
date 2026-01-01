<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/GoogleAuthenticator.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Add columns if not exist
try {
    $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'is_2fa_enabled'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN is_2fa_enabled TINYINT(1) DEFAULT 0, ADD COLUMN two_factor_secret VARCHAR(32) DEFAULT NULL");
    }
} catch (PDOException $e) {}

$ga = new PHPGangsta_GoogleAuthenticator();
$action = $_GET['action'] ?? '';

if ($action === 'status') {
    $stmt = $pdo->prepare("SELECT is_2fa_enabled FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $status = $stmt->fetchColumn();
    echo json_encode(['status' => 'success', 'enabled' => (bool)$status]);
    exit;
}

if ($action === 'generate') {
    $secret = $ga->createSecret();
    $qrCodeUrl = $ga->getQRCodeGoogleUrl('BeePee', $secret);
    
    // Store secret temporarily or in DB (but not enabled yet)
    $stmt = $pdo->prepare("UPDATE users SET two_factor_secret = ? WHERE id = ?");
    $stmt->execute([$secret, $_SESSION['user_id']]);
    
    echo json_encode([
        'status' => 'success',
        'secret' => $secret,
        'qr_code_url' => $qrCodeUrl
    ]);
    exit;
}

if ($action === 'verify' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $code = $data['code'] ?? '';
    
    $stmt = $pdo->prepare("SELECT two_factor_secret FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $secret = $stmt->fetchColumn();
    
    if ($ga->verifyCode($secret, $code, 2)) {
        $stmt = $pdo->prepare("UPDATE users SET is_2fa_enabled = 1 WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        logActivity($pdo, $_SESSION['user_id'], '2fa_enable', 'Two-factor authentication enabled');
        
        echo json_encode(['status' => 'success', 'message' => '2FA Enabled Successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Code']);
    }
    exit;
}

if ($action === 'disable' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';

    // Verify password first
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $hash = $stmt->fetchColumn();

    if (password_verify($password, $hash)) {
        $stmt = $pdo->prepare("UPDATE users SET is_2fa_enabled = 0, two_factor_secret = NULL WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        
        logActivity($pdo, $_SESSION['user_id'], '2fa_disable', 'Two-factor authentication disabled');

        echo json_encode(['status' => 'success', 'message' => '2FA Disabled']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Password']);
    }
    exit;
}
?>