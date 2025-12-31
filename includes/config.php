<?php
// includes/config.php

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
