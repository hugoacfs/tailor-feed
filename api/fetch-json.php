<?php
$username = $_GET['user'] ?? 'default';
//Server url
$url = "http://localhost/tailor-feed/json.php?username=$username&page=1&mode=json";
$apiKey = $CFG->json_secret; // should match with Server key
$headers = array(
    'api_key: ' . $apiKey
);
// Send request to Server
$ch = curl_init($url);
// To save response in a variable from server, set headers;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// Get response
$response = curl_exec($ch);
// Decode
$result = json_decode($response);
print_r($result);
