<?php
// includes/config.php

// Ensure DB connection is available for session handling
require_once __DIR__ . '/db.php';

class PdoSessionHandler implements SessionHandlerInterface {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function open($savePath, $sessionName) {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        try {
            $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result['data'] : '';
        } catch (PDOException $e) {
            return '';
        }
    }

    public function write($id, $data) {
        try {
            $access = time();
            $stmt = $this->pdo->prepare("REPLACE INTO sessions (id, access, data) VALUES (:id, :access, :data)");
            return $stmt->execute([':id' => $id, ':access' => $access, ':data' => $data]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function destroy($id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function gc($maxlifetime) {
        try {
            $old = time() - $maxlifetime;
            $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE access < :old");
            return $stmt->execute([':old' => $old]);
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
