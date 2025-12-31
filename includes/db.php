<?php
// includes/db.php

// TiDB / MySQL Configuration
$host = getenv('TIDB_HOST') ?: 'gateway01.us-east-1.prod.aws.tidbcloud.com';
$port = getenv('TIDB_PORT') ?: '4000';
$dbname = getenv('TIDB_DATABASE') ?: 'test';
$username = getenv('TIDB_USER') ?: '41ik8s2c8p3P2AD.root';
$password = getenv('TIDB_PASSWORD') ?: '1L7Wjvxu7WNo5SLE';
$ssl_ca = getenv('TIDB_CA') ?: null; // Optional if needed

$dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false // TiDB often requires SSL but verification might be tricky without the CA file locally
];

// If no CA provided in env, and we are connecting to TiDB, we usually need SSL. 
// Standard PDO MySQL often works with TiDB if SSL is just enabled. 
// Let's try basic connection first.

try {
    $pdo = new PDO($dsn, $username, $password, $options);

    // Create table if not exists - MySQL Syntax
    $sql = "CREATE TABLE IF NOT EXISTS health_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        log_date DATE NOT NULL,
        systolic INT,
        diastolic INT,
        blood_sugar DECIMAL(5,2),
        weight DECIMAL(5,2),
        notes TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    
    $pdo->exec($sql);

} catch (PDOException $e) {
    // Fallback to SQLite if MySQL fails (for local dev without internet/creds)
    // Or just die with error. Given the user explicitly provided creds, we should probably die with error so they know.
    die("Database connection failed: " . $e->getMessage());
}
?>