<?php
// includes/security_headers.php

// Prevent clickjacking
header("X-Frame-Options: SAMEORIGIN");

// Prevent MIME type sniffing
header("X-Content-Type-Options: nosniff");

// Enable XSS filtering
header("X-XSS-Protection: 1; mode=block");

// Referrer Policy
header("Referrer-Policy: strict-origin-when-cross-origin");

// Content Security Policy (Basic) - Allow CDN scripts for Tailwind, Chart.js, Toastify
// Note: 'unsafe-inline' is used for simplicity with Tailwind and inline scripts, ideally should be removed in stricter environments
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com https://cdn.jsdelivr.net; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; connect-src 'self'");

// Strict Transport Security (HSTS) - Enforce HTTPS (1 year)
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
}
?>
