<?php
define('CONFIG_PROTECTION', false);
session_start();
require_once(__DIR__ . '/../config.php');
cors();
if (in_array($_SERVER['HTTP_ORIGIN'], $CFG->authorised_cors)) die;
$username = $_POST['userName'] ?? 'default';
//Server url
$mode = $_GET['mode'] ?? 'json';
$url = $CFG->api_url . "/json.php?user=$username&page=1&mode=$mode";
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
$loginLink = $CFG->api_url . '/login.php';
$response = '';
//if (false) {
//    $response = '
//<div class="card-body">
//    <a href="' . $loginLink . '" target="news" class="btn btn-primary text-white float-right">
//        <i class="fas fa-cog" aria-hidden="true">
//        </i> Preferences
//    </a>
//</div>
//<br>';
//}
$response .= curl_exec($ch) ?? '';
// Add end of response message, leading them to website to see more news.
$response_end .= '
<div class="card-body ">
    <h4 class="card-title">
        <a class=" card-link">
            That\'s all for now...
        </a>
    </h4>
    <p class="card-text">
        <a href="' . $loginLink . '" target="news" class="">
            <i class="fas fa-sign-out-alt" aria-hidden="true">
            </i> Show More News
        </a>
    </p>
    <span style="display: none;">newscode:340</span>
</div>';
echo ($response);