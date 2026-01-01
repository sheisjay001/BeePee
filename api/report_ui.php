<?php
include __DIR__ . '/../includes/config.php';
include __DIR__ . '/../includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login_ui.php");
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch logs
try {
    $stmt = $pdo->prepare("SELECT * FROM health_logs WHERE user_id = ? ORDER BY log_date DESC, created_at DESC");
    $stmt->execute([$userId]);
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Error fetching logs");
}

// Calculate Stats
$avgSys = $avgDia = $avgSugar = 0;
if (count($logs) > 0) {
    $sys = array_filter(array_column($logs, 'systolic'));
    $dia = array_filter(array_column($logs, 'diastolic'));
    $sugar = array_filter(array_column($logs, 'blood_sugar'));

    if (count($sys)) $avgSys = round(array_sum($sys) / count($sys));
    if (count($dia)) $avgDia = round(array_sum($dia) / count($dia));
    if (count($sugar)) $avgSugar = round(array_sum($sugar) / count($sugar));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Health Report - <?php echo htmlspecialchars($username); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { background: white; }
            .shadow { box-shadow: none; }
        }
    </style>
</head>
<body class="bg-gray-50 p-8">

    <div class="max-w-4xl mx-auto bg-white p-8 shadow-lg print:shadow-none">
        
        <!-- Header -->
        <div class="flex justify-between items-start mb-8 border-b pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Health Report</h1>
                <p class="text-gray-500 mt-1">Generated on <?php echo date('F j, Y'); ?></p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-semibold text-primary"><?php echo htmlspecialchars($username); ?></h2>
                <p class="text-sm text-gray-500">BeePee Health Tracker</p>
            </div>
        </div>

        <!-- Summary Stats -->
        <div class="grid grid-cols-3 gap-6 mb-8 bg-gray-50 p-6 rounded-lg print:bg-white print:border">
            <div class="text-center">
                <p class="text-sm text-gray-500 uppercase tracking-wide">Avg Systolic</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo $avgSys ?: '--'; ?> <span class="text-sm font-normal text-gray-500">mmHg</span></p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 uppercase tracking-wide">Avg Diastolic</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo $avgDia ?: '--'; ?> <span class="text-sm font-normal text-gray-500">mmHg</span></p>
            </div>
            <div class="text-center">
                <p class="text-sm text-gray-500 uppercase tracking-wide">Avg Blood Sugar</p>
                <p class="text-3xl font-bold text-gray-900"><?php echo $avgSugar ?: '--'; ?> <span class="text-sm font-normal text-gray-500">mg/dL</span></p>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="overflow-hidden border rounded-lg">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 print:bg-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Pressure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Sugar</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (count($logs) === 0): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No logs found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($log['log_date']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if($log['systolic']): ?>
                                    <span class="<?php echo ($log['systolic'] > 140 || $log['diastolic'] > 90) ? 'text-red-600 font-medium' : ''; ?>">
                                        <?php echo htmlspecialchars($log['systolic'] . '/' . $log['diastolic']); ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($log['blood_sugar'] ?: '-'); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?php echo htmlspecialchars($log['weight'] ? $log['weight'].' kg' : '-'); ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic max-w-xs truncate"><?php echo htmlspecialchars($log['notes'] ?: '-'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Print Button (Hidden in Print) -->
        <div class="mt-8 text-center no-print space-x-4">
            <button onclick="window.print()" class="bg-primary hover:bg-secondary text-white font-bold py-2 px-6 rounded shadow transition">
                Print / Save as PDF
            </button>
            <a href="tracker_ui.php" class="text-gray-600 hover:text-gray-900 font-medium">Back to Dashboard</a>
        </div>

    </div>

</body>
</html>