<?php
// api/tracker.php
header('Content-Type: application/json');
require_once '../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch logs
    try {
        $stmt = $pdo->query("SELECT * FROM health_logs ORDER BY log_date ASC, created_at ASC");
        $logs = $stmt->fetchAll();
        echo json_encode(['status' => 'success', 'data' => $logs]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    // Add new log
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
        exit;
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO health_logs (log_date, systolic, diastolic, blood_sugar, weight, notes) VALUES (:date, :sys, :dia, :sugar, :weight, :notes)");
        $stmt->execute([
            ':date' => $data['date'] ?? date('Y-m-d'),
            ':sys' => $data['systolic'] ?? null,
            ':dia' => $data['diastolic'] ?? null,
            ':sugar' => $data['blood_sugar'] ?? null,
            ':weight' => $data['weight'] ?? null,
            ':notes' => $data['notes'] ?? ''
        ]);
        
        echo json_encode(['status' => 'success', 'id' => $pdo->lastInsertId()]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
}
?>
