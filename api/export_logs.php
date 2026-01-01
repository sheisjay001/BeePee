<?php
// api/export_logs.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    die('Unauthorized');
}

$userId = $_SESSION['user_id'];
$filename = "beepee_health_logs_" . date('Y-m-d') . ".csv";

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

$output = fopen('php://output', 'w');

// Add BOM for Excel compatibility
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Header row
fputcsv($output, ['Date', 'Systolic (mmHg)', 'Diastolic (mmHg)', 'Blood Sugar (mg/dL)', 'Weight (kg)', 'Notes']);

try {
    $stmt = $pdo->prepare("SELECT log_date, systolic, diastolic, blood_sugar, weight, notes FROM health_logs WHERE user_id = ? ORDER BY log_date DESC");
    $stmt->execute([$userId]);
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        fputcsv($output, [
            $row['log_date'],
            $row['systolic'] ?: '-',
            $row['diastolic'] ?: '-',
            $row['blood_sugar'] ?: '-',
            $row['weight'] ?: '-',
            $row['notes'] ?: ''
        ]);
    }
} catch (Exception $e) {
    // In a CSV download, we can't easily return JSON error, so we might just log it or output error in file
    error_log($e->getMessage());
}

fclose($output);
exit;
?>