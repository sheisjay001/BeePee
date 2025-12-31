<?php
// api/chat.php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
$userMessage = $input['message'] ?? '';

if (empty($userMessage)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Message is required']);
    exit;
}

if (GROQ_API_KEY === 'your_groq_api_key_here') {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'API Key not configured']);
    exit;
}

// Fetch recent health logs for context
$contextMsg = "";
try {
    $stmt = $pdo->query("SELECT log_date, systolic, diastolic, blood_sugar, weight FROM health_logs ORDER BY log_date DESC LIMIT 5");
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($logs) {
        $contextMsg = "Here is the user's recent health data (Date: Systolic/Diastolic BP, Sugar, Weight): ";
        foreach ($logs as $log) {
            $contextMsg .= "[{$log['log_date']}: {$log['systolic']}/{$log['diastolic']} mmHg, Sugar: {$log['blood_sugar']} mg/dL, Weight: {$log['weight']} kg]; ";
        }
        $contextMsg .= ". Use this data to provide personalized advice if relevant to the question.";
    }
} catch (Exception $e) {
    // Ignore DB errors for chat context, just proceed without it
}

$systemPrompt = 'You are BeePee AI, a helpful and knowledgeable nutritionist and health assistant. Your goal is to educate people on the correct diet to stabilize their blood sugar and blood pressure. You provide scientific, safe, and practical advice. Always remind users to consult with a doctor for serious medical conditions. Keep your answers concise, encouraging, and easy to understand.';

if (!empty($contextMsg)) {
    $systemPrompt .= " " . $contextMsg;
}

$payload = [
    'model' => 'llama3-70b-8192',
    'messages' => [
        [
            'role' => 'system',
            'content' => $systemPrompt
        ],
        [
            'role' => 'user',
            'content' => $userMessage
        ]
    ]
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . GROQ_API_KEY
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => curl_error($ch)]);
} else {
    http_response_code($httpCode);
    echo $response;
}

curl_close($ch);
?>
