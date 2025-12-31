<?php
// includes/config.php

// Check for local config override (ignored by git)
if (file_exists(__DIR__ . '/config.local.php')) {
    include_once __DIR__ . '/config.local.php';
}

// Use Environment Variable (Vercel) OR Local Override OR Default Placeholder
define('GROQ_API_KEY', getenv('GROQ_API_KEY') ?: (defined('GROQ_API_KEY_LOCAL') ? GROQ_API_KEY_LOCAL : 'your_groq_api_key_here'));
?>
