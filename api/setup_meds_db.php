<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    // Create Medications Table
    $sql1 = "CREATE TABLE IF NOT EXISTS medications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        name VARCHAR(255) NOT NULL,
        dosage VARCHAR(100),
        frequency VARCHAR(50),
        schedule_time TIME,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql1);
    echo "Medications table created successfully.<br>";

    // Create Medication Logs Table
    $sql2 = "CREATE TABLE IF NOT EXISTS medication_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        medication_id INT NOT NULL,
        taken_date DATE NOT NULL,
        taken_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (medication_id) REFERENCES medications(id) ON DELETE CASCADE
    )";
    $pdo->exec($sql2);
    echo "Medication logs table created successfully.<br>";

} catch (PDOException $e) {
    die("DB Error: " . $e->getMessage());
}
?>