<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$userId = $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];

// Auto-create tables if not exist (Migration)
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS medications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        dosage VARCHAR(100),
        frequency VARCHAR(50),
        schedule_time TIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )");
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS medication_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        medication_id INT NOT NULL,
        taken_date DATE NOT NULL,
        taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE
    )");
} catch (PDOException $e) {
    // Ignore if exists or error, logging would be good
}

if ($method === 'GET') {
    try {
        // Get all medications
        $stmt = $pdo->prepare("SELECT m.*, 
            (SELECT COUNT(*) FROM medication_logs l WHERE l.medication_id = m.id AND l.taken_date = CURDATE()) as taken_today
            FROM medications m WHERE m.user_id = ? ORDER BY m.schedule_time ASC");
        $stmt->execute([$userId]);
        $meds = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode(['status' => 'success', 'data' => $meds]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} elseif ($method === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (isset($data['action']) && $data['action'] === 'toggle') {
        // Toggle Taken Status
        $medId = $data['id'];
        
        try {
            // Check if taken today
            $check = $pdo->prepare("SELECT id FROM medication_logs WHERE medication_id = ? AND taken_date = CURDATE()");
            $check->execute([$medId]);
            $log = $check->fetch();
            
            if ($log) {
                // Untake (Delete log)
                $del = $pdo->prepare("DELETE FROM medication_logs WHERE id = ?");
                $del->execute([$log['id']]);
                echo json_encode(['status' => 'success', 'message' => 'Marked as not taken']);
            } else {
                // Take (Insert log)
                $ins = $pdo->prepare("INSERT INTO medication_logs (medication_id, taken_date) VALUES (?, CURDATE())");
                $ins->execute([$medId]);
                echo json_encode(['status' => 'success', 'message' => 'Marked as taken']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    } else {
        // Add Medication
        $name = $data['name'] ?? '';
        $dosage = $data['dosage'] ?? '';
        $time = $data['time'] ?? '';
        
        if (empty($name)) {
            echo json_encode(['status' => 'error', 'message' => 'Medication name is required']);
            exit;
        }
        
        try {
            $stmt = $pdo->prepare("INSERT INTO medications (user_id, name, dosage, schedule_time) VALUES (?, ?, ?, ?)");
            $stmt->execute([$userId, $name, $dosage, $time]);
            echo json_encode(['status' => 'success', 'message' => 'Medication added']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
} elseif ($method === 'DELETE') {
    $data = json_decode(file_get_contents('php://input'), true);
    $medId = $data['id'] ?? null;
    
    if (!$medId) {
        echo json_encode(['status' => 'error', 'message' => 'ID required']);
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("DELETE FROM medications WHERE id = ? AND user_id = ?");
        $stmt->execute([$medId, $userId]);
        echo json_encode(['status' => 'success', 'message' => 'Medication deleted']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
?>