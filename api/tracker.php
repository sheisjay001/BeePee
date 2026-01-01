<?php
// api/tracker.php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

verify_csrf_or_die();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    // Fetch logs for user
    try {
        $stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date ASC, created_at ASC");
        $stmt->execute([$userId]);
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch Assoc for cleaner JSON

        // Calculate Streak
        $stmtDate = $pdo->prepare("SELECT DISTINCT DATE(log_date) as log_date FROM health_logs WHERE user_id = ? ORDER BY log_date DESC");
        $stmtDate->execute([$userId]);
        $dates = $stmtDate->fetchAll(PDO::FETCH_COLUMN);

        $streak = 0;
        if (!empty($dates)) {
            $today = new DateTime();
            $yesterday = new DateTime('yesterday');
            $lastLog = new DateTime($dates[0]);

            // Streak continues if last log was today or yesterday
            if ($lastLog->format('Y-m-d') === $today->format('Y-m-d') || 
                $lastLog->format('Y-m-d') === $yesterday->format('Y-m-d')) {
                
                $streak = 1;
                $currentDate = $lastLog;

                for ($i = 1; $i < count($dates); $i++) {
                    $prevDate = new DateTime($dates[$i]);
                    $diff = $currentDate->diff($prevDate)->days;
                    
                    if ($diff === 1) {
                        $streak++;
                        $currentDate = $prevDate;
                    } else {
                        break;
                    }
                }
            }
        }

        echo json_encode(['status' => 'success', 'data' => $logs, 'streak' => $streak]);
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
        $stmt = $pdo->prepare("INSERT INTO health_logs (user_id, log_date, systolic, diastolic, blood_sugar, weight, notes) VALUES (:uid, :date, :sys, :dia, :sugar, :weight, :notes)");
        $stmt->execute([
            ':uid' => $userId,
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
