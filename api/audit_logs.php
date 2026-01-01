<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    // Check if table exists (in case it wasn't created yet)
    $stmt = $pdo->prepare("SHOW TABLES LIKE 'audit_logs'");
    $stmt->execute();
    if ($stmt->rowCount() == 0) {
         echo json_encode(['status' => 'success', 'data' => []]);
         exit;
    }

    $stmt = $pdo->prepare("SELECT action, details, ip_address, created_at FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT 20");
    $stmt->execute([$_SESSION['user_id']]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $logs]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Database error']);
}
?>