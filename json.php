<?php
error_reporting(0);
function forbidRequest()
{
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
$headers = apache_request_headers();
if (isset($headers['api_key'])) $api_key = $headers['api_key'];
else forbidRequest();
define('CONFIG_PROTECTION', false);
$title = 'JSON RESULT';
require_once __DIR__ . '/config.php';
$key_not_match = $api_key != $CFG->json_secret;
if ($key_not_match) forbidRequest();
$username = $_GET['user'] ?? 'default';
$user = new User($username);
$page = $_GET['page'] ?? 1;
$mode = $_GET['mode'] ?? 'json';
switch($mode){
    case 'json':
        header('Content-Type: application/json');
        $data = $user->getArticlesJSON($page);
        break;
    case 'html':
        header('Content-Type: text/html; charset=UTF-8');
        $data = $user->displaySubscribedArticles($page);
        break;
}
echo ($data);
