<?php

/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 */
function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        if ($_SERVER['HTTP_ORIGIN'] != 'your-website.com') exit('Your site is not whitelisted!');
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    //echo "You have CORS!";
}
cors();
define('CONFIG_PROTECTION', false);
session_start();
require_once __DIR__ . '/../config.php';
$username = $_SESSION['userName'] ?? 'default';
//Server url
$url = $CFG->api_url . "?user=$username&page=1&mode=html";
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
$loginLink = 'login.php';
$response = '';
if($username != 'default'){
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
        <button onclick="location.href=\'' . $loginLink . '\'" class="btn btn-primary mr-1 ml-1 border">
            <i class="fas fa-sign-out-alt" aria-hidden="true">
            </i> Login to News
        </button>
    </p>
    <span style="display: none;">newscode:340</span>
</div>';
echo ($response);
