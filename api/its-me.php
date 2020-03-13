<?php
define('CONFIG_PROTECTION', false);
session_start();
require_once __DIR__ . '/../config.php';
cors();
echo '<pre>';
print_r(getallheaders());
echo '</pre>';

$user = $_SESSION['USER'] ?? new SSAML();
$username = $_SESSION['USER']->getUserName() ?? new SSAML();
//Server url
$url = $CFG->api_url . "/json.php?user=$username&page=1&mode=html";
// Send request to Server
$ch = curl_init($url);
// To save response in a variable from server, set headers;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// Get response
$response = curl_exec($ch) ?? '';
// Add end of response message, leading them to website to see more news.
