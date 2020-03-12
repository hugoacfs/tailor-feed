<?php
define('CONFIG_PROTECTION', false);

require_once __DIR__ . '/../config.php';
$username = $_SESSION['username'] ?? 'default';
//Server url
$url = $CFG->api_url."?user=$username&page=1&mode=html";
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
echo ($response);
