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
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        // Password correct
        // Clear attempts on success
        $pdo->prepare("DELETE FROM login_attempts WHERE ip_address = ?")->execute([$ip_address]);

        // Prevent Session Fixation
        session_regenerate_id(true);

        // Check 2FA
        if (!empty($user['is_2fa_enabled']) && $user['is_2fa_enabled'] == 1) {
             $_SESSION['2fa_pending_user_id'] = $user['id'];
             $_SESSION['2fa_pending_username'] = $user['username'];
             echo json_encode(['status' => '2fa_required', 'message' => 'Two-factor authentication required']);
             exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        logActivity($pdo, $user['id'], 'login', 'Logged in successfully');

        echo json_encode(['status' => 'success', 'message' => 'Login successful']);
    } else {
        // Log failed attempt
        $pdo->prepare("INSERT INTO login_attempts (ip_address, attempt_time) VALUES (?, ?)")->execute([$ip_address, time()]);

        // Security: Log to audit_logs if user exists (so they know someone tried to login)
        if ($user) {
             // Update User Lockout Counters
             $new_failures = ($user['failed_login_attempts'] ?? 0) + 1;
             $lockout_until = null;
             $lockout_msg = "";
             
             // Lockout logic: Lock for 15 mins after every 5th attempt
             if ($new_failures % 5 == 0) {
                 $lockout_until = date('Y-m-d H:i:s', time() + (15 * 60)); // 15 minutes
                 $lockout_msg = " Account locked for 15 minutes.";
             }
             
             $pdo->prepare("UPDATE users SET failed_login_attempts = ?, lockout_until = ? WHERE id = ?")
                 ->execute([$new_failures, $lockout_until, $user['id']]);

             logActivity($pdo, $user['id'], 'login_failed', "Failed login attempt from IP: $ip_address.$lockout_msg");
             
             if ($lockout_until) {
                 http_response_code(429);
                 echo json_encode(['status' => 'error', 'message' => "Account locked due to too many failed attempts. Try again in 15 minutes."]);
                 exit;
             }
        }

        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email or password']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
