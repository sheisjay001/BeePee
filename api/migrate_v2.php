<?php
// api/migrate_v2.php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

try {
    // Add height column to users table if it doesn't exist
    $check = $pdo->query("SHOW COLUMNS FROM users LIKE 'height'");
    if ($check->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD COLUMN height DECIMAL(5,2) NULL AFTER email");
        echo "Added 'height' column to 'users' table.\n";
    } else {
        echo "'height' column already exists.\n";
    }

    echo "Migration completed successfully.";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage();
}
?>