<?php
define('CONFIG_PROTECTION', false);
session_start();
require_once __DIR__ . '/../config.php';
cors();
$username = $_SESSION['userName'] ?? 'default';
//Server url
$url = $CFG->api_url . "/json.php?user=$username&page=1&mode=html";
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
if ($username != 'default') {
    $response = '
<div class="card-body">
    <a href="' . $loginLink . '" target="news" class="btn btn-primary text-white float-right">
        <i class="fas fa-cog" aria-hidden="true">
        </i> Preferences
    </a>
</div>
<br>';
}
$response .= curl_exec($ch) ?? '';
// Add end of response message, leading them to website to see more news.
$response .= '
<div class="card-body ">
    <h4 class="card-title">
        <a class=" card-link">
            That\'s all for now...
        </a>
    </h4>
    <p class="card-text">To see more university news, login to the News Site.
        <a href="' . $loginLink . '" target="news" class="btn btn-primary text-white">
            <i class="fas fa-sign-out-alt" aria-hidden="true">
            </i> Login to News
        </a>
    </p>
    <span style="display: none;">newscode:340</span>
</div>';
echo ($response);
