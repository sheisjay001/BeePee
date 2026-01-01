<?php
// includes/csrf.php

if (session_status() === PHP_SESSION_NONE) {
    // Session should be started in config.php, but just in case
    // We don't start it here to avoid conflicts if config.php handles it differently
}

/**
 * Generate a CSRF token and store it in the session.
 * @return string The CSRF token.
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate the CSRF token from the request.
 * @param string|null $token The token to validate. If null, looks in headers/POST.
 * @return bool True if valid, false otherwise.
 */
function validate_csrf_token($token = null) {
    if (empty($_SESSION['csrf_token'])) {
        return false;
    }

    if ($token === null) {
        // Check header first (common for AJAX/Fetch)
        $headers = array_change_key_case(getallheaders(), CASE_LOWER);
        if (isset($headers['x-csrf-token'])) {
            $token = $headers['x-csrf-token'];
        } 
        // Then check POST data
        elseif (isset($_POST['csrf_token'])) {
            $token = $_POST['csrf_token'];
        }
        // Then check JSON body if applicable
        else {
            $input = json_decode(file_get_contents('php://input'), true);
            if (isset($input['csrf_token'])) {
                $token = $input['csrf_token'];
            }
        }
    }

    return hash_equals($_SESSION['csrf_token'], (string)$token);
}

/**
 * Middleware to enforce CSRF protection on POST/PUT/DELETE requests.
 * Terminates execution if validation fails.
 */
function verify_csrf_or_die() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if (!validate_csrf_token()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'CSRF token validation failed. Please refresh the page.']);
            exit;
        }
    }
}
?>