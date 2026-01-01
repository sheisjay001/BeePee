<?php
// api/login.php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

verify_csrf_or_die();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$ip_address = $_SERVER['REMOTE_ADDR'];
$time_window = 300; // 5 minutes
$max_attempts = 5;

// Clean up old attempts
$pdo->prepare("DELETE FROM login_attempts WHERE attempt_time < ?")->execute([time() - $time_window]);

// Check attempt count
$stmt = $pdo->prepare("SELECT COUNT(*) FROM login_attempts WHERE ip_address = ?");
$stmt->execute([$ip_address]);
$attempts = $stmt->fetchColumn();

if ($attempts >= $max_attempts) {
    http_response_code(429);
    echo json_encode(['status' => 'error', 'message' => 'Too many login attempts. Please try again in 5 minutes.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Email and password are required']);
    exit;
}

try {
    // Fetch user
    $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Password correct
        // Clear attempts on success
        $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?")->execute([$ip_address]);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        // Log failed attempt
        $pdo->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, ?)")->execute([$ip_address, time()]);

        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
