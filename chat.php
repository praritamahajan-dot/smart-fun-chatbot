<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if(isset($_POST['message']) && !empty($_POST['message'])) {
    $message = $_POST['message'];
    
    // YOUR KEY HERE (100% SAFE)
    $api_key = "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";
    
    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            ['role' => 'system', 'content' => 'You are Study & Fun Bot assistant.'],
            ['role' => 'user', 'content' => $message]
        ],
        'temperature' => 0.7,
        'max_tokens' => 400
    ];
    
    $ch = curl_init('https://api.openai.com/v1/chat/completions');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $api_key,
        'Content-Type: application/json'
    ]);
    
    $response = curl_exec($ch);
    curl_close($ch);
    echo $response ?: json_encode(['error' => 'API unavailable']);
} else {
    echo json_encode(['error' => 'No message']);
}
?>
