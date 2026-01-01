<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$token = $data['token'] ?? '';
$email = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (empty($token) || empty($email) || empty($password)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required fields']);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters']);
    exit;
}

try {
    // Check token
    $stmt = $pdo->prepare("SELECT created_at FROM password_resets WHERE email = ? AND token = ?");
    $stmt->execute([$email, $token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid or expired token']);
        exit;
    }

    // Check expiry (1 hour)
    $createdAt = strtotime($resetRequest['created_at']);
    if (time() - $createdAt > 3600) {
        echo json_encode(['status' => 'error', 'message' => 'Token has expired']);
        exit;
    }

    // Update Password
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$hash, $email]);

    // Delete Token
    $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
    $stmt->execute([$email]);

    // Get User ID for logging
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $userId = $stmt->fetchColumn();

    if ($userId) {
        logActivity($pdo, $userId, 'password_reset', 'Password reset via email');
    }

    echo json_encode(['status' => 'success', 'message' => 'Password reset successfully']);

} catch (PDOException $e) {
    error_log($e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
}
?>