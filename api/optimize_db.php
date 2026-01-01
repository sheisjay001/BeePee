<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

echo "Optimizing Database...\n";

function addIndex($pdo, $table, $indexName, $columns) {
    try {
        // Check if index exists (MySQL specific)
        $check = $pdo->query("SHOW INDEX FROM $table WHERE Key_name = '$indexName'");
        if ($check->fetch()) {
            echo "Index $indexName on $table already exists.\n";
            return;
        }
        
        $sql = "CREATE INDEX $indexName ON $table ($columns)";
        $pdo->exec($sql);
        echo "Created index $indexName on $table.\n";
    } catch (PDOException $e) {
        echo "Error creating index $indexName on $table: " . $e->getMessage() . "\n";
    }
}

// Health Logs
addIndex($pdo, 'health_logs', 'idx_health_logs_user_date', 'user_id, log_date');

// Medication Logs
addIndex($pdo, 'medication_logs', 'idx_med_logs_med_date', 'medication_id, taken_date');

// Medications
addIndex($pdo, 'medications', 'idx_medications_user', 'user_id');

echo "Database optimization complete.\n";
?>
