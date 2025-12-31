<?php
// api/logout.php
require_once __DIR__ . '/../includes/config.php';

session_destroy();
header('Content-Type: application/json');
echo json_encode(['status' => 'success', 'message' => 'Logged out']);
?>
