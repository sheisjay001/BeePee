<?php
function logActivity($pdo, $userId, $action, $details = '') {
    try {
        // Auto-create table if needed (simple check, better to have dedicated migration but this works for drop-in)
        // We use a static variable to avoid running this query on every single log in a request, 
        // though in PHP's shared-nothing architecture it runs once per request anyway.
        // For performance, we might want to move this to a setup script, but for this task, it ensures robustness.
        $pdo->exec("CREATE TABLE IF NOT EXISTS audit_logs (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            action VARCHAR(50) NOT NULL,
            details TEXT,
            ip_address VARCHAR(45),
            user_agent TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        )");

        $ip = $_SERVER['REMOTE_ADDR'] ?? '';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

        $stmt = $pdo->prepare("INSERT INTO audit_logs (user_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$userId, $action, $details, $ip, $ua]);
    } catch (PDOException $e) {
        // Silent failure to not disrupt user flow
        error_log("Audit Log Error: " . $e->getMessage());
    }
}
?>