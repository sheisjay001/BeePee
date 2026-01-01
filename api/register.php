<?php
// api/register.php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

verify_csrf_or_die();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
    exit;
}

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (empty($username) || empty($email) || empty($password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
    exit;
}

// Password Strength Validation
if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Password must be at least 8 characters long']);
    exit;
}
if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Password must contain both letters and numbers']);
    exit;
}

try {
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['status' => 'error', 'message' => 'User already exists']);
        exit;
    }

    // Hash password
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    $stmt->execute([$username, $email, $passwordHash]);

    $userId = $pdo->lastInsertId();

    // Start session and log user in
    $_SESSION['user_id'] = $userId;
    $_SESSION['username'] = $username;

    echo json_encode(['status' => 'success', 'message' => 'Registration successful']);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
}
?>
