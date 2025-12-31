<?php
// api/generate_meal.php
header('Content-Type: application/json');
require_once __DIR__ . '/../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$ingredients = $data['ingredients'] ?? '';

if (empty($ingredients)) {
    http_response_code(400);
    echo json_encode(['error' => 'Ingredients are required']);
    exit;
}

if (GROQ_API_KEY === 'your_groq_api_key_here' || empty(GROQ_API_KEY)) {
    http_response_code(500);
    echo json_encode(['error' => 'Configuration Error: GROQ_API_KEY is missing.']);
    exit;
}

// System prompt to act as a nutritionist
$systemPrompt = "You are an expert nutritionist and chef for BeePee, an app for managing blood pressure and blood sugar. 
Your task is to generate a delicious, healthy recipe based on the user's available ingredients.
The recipe MUST be low in sodium and low in glycemic index.
Return the response in strict JSON format with the following structure:
{
    'title': 'Recipe Title',
    'description': 'Brief description of why this is good for BP/Sugar',
    'prep_time': 'XX mins',
    'cook_time': 'XX mins',
    'ingredients': ['qty item', 'qty item'],
    'instructions': ['step 1', 'step 2'],
    'macros': {'calories': 'XXX', 'protein': 'XXg', 'carbs': 'XXg', 'fats': 'XXg'}
}
Do not include any markdown formatting or extra text, just the JSON.";

$userPrompt = "I have the following ingredients: " . $ingredients . ". Please generate a healthy meal.";

$payload = [
    'model' => 'llama3-70b-8192',
    'messages' => [
        ['role' => 'system', 'content' => $systemPrompt],
        ['role' => 'user', 'content' => $userPrompt]
    ],
    'temperature' => 0.7,
    'response_format' => ['type' => 'json_object'] 
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . GROQ_API_KEY,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for local XAMPP SSL issues

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode(['error' => 'Curl error: ' . curl_error($ch)]);
    exit;
}

curl_close($ch);

if ($httpCode !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'API Request failed', 'details' => $response]);
    exit;
}

$result = json_decode($response, true);
$aiContent = $result['choices'][0]['message']['content'] ?? '{}';

// Ensure we return valid JSON
echo $aiContent;
?>
