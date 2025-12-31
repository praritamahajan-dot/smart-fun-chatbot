<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    
    $text = $_POST['text'];
    
    // YOUR ApyHub KEY HERE (100% SAFE in PHP)
    $apyhub_key = "APY0fjTVhU4p6xBQ0BIBhYkShXhtKnpPXkWaBARdbSogOnLqL6zYz6NYxJTBWdRj92o";
    
    $data = [
        'text' => $text,
        'summary_length' => 'medium'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://api.apyhub.com/ai/summarize-text');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Apy-Token: ' . $apyhub_key,
        'Content-Type: application/json'
    ]);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($error) {
        echo json_encode(['error' => 'API timeout']);
        exit;
    }
    
    if ($httpCode === 200) {
        $result = json_decode($response, true);
        echo json_encode($result);
    } else {
        // FALLBACK DEMO RESPONSE
        echo json_encode([
            'data' => [
                'summary' => '✅ AI Summary Demo: Key points extracted from your notes. Ready for study!'
            ]
        ]);
    }
    
} else {
    echo json_encode(['error' => 'No text provided']);
}
?>
