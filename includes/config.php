<?php
// includes/config.php

require_once __DIR__ . '/security_headers.php';

// Ensure DB connection is available for session handling
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/logger.php';

class PdoSessionHandler implements SessionHandlerInterface {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function open(string $path, string $name): bool {
        return true;
    }

    public function close(): bool {
        return true;
    }

    public function read(string $id): string|false {
        try {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['data'] : '';
        } catch (PDOException $e) {
            return '';
        }
    }

    public function write(string $id, string $data): bool {
        try {
            $access = time();
            $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, access, data) VALUES (:id, :access, :data)");
            return $stmt->execute([':id' => $id, ':access' => $access, ':data' => $data]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function destroy(string $id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function gc(int $max_lifetime): int|false {
        try {
            $old = time() - $max_lifetime;
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE access < :old");
            $stmt->execute([':old' => $old]);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return false;
        }
    }
}

// Set up custom session handler if PDO is available
if (isset($pdo)) {
    $handler = new PdoSessionHandler($pdo);
    session_set_save_handler($handler, true);
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper to get env var from various sources
function get_env_var($key) {
    // Check getenv
    $val = getenv($key);
    if ($val !== false && $val !== '') return $val;
    
    // Check $_ENV
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    
    // Check $_SERVER
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    
    return false;
}

require_once __DIR__ . '/csrf.php';

// Check for local config override (ignored by git)
if (file_exists(__DIR__ . '/config.local.php')) {
    include_once __DIR__ . '/config.local.php';
}

$apiKey = get_env_var('GROQ_API_KEY');

if (!$apiKey && defined('GROQ_API_KEY_LOCAL')) {
    $apiKey = GROQ_API_KEY_LOCAL;
}

if (!$apiKey) {
    $apiKey = 'your_groq_api_key_here';
}

define('GROQ_API_KEY', $apiKey);
?>
