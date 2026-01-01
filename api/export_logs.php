<?php
// api/export_logs.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    die('Unauthorized');
}

$userId = $_SESSION['user_id'];

// Fetch logs
try {
    $stmt = $pdo->prepare("SELECT log_date, systolic, diastolic, blood_sugar, weight, notes, created_at FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, created_at DESC");
    $stmt->execute([$userId]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching data");
}

// Set headers for CSV download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=health_logs_' . date('Y-m-d') . '.csv');

// Create file pointer connected to output stream
$output = fopen('php://output', 'w');

// Output column headings
fputcsv($output, array('Date', 'Systolic BP (mmHg)', 'Diastolic BP (mmHg)', 'Blood Sugar (mg/dL)', 'Weight (kg)', 'Notes', 'Created At'));

// Loop over the rows, outputting them
foreach ($logs as $row) {
    fputcsv($output, $row);
}

fclose($output);
exit;
?>
