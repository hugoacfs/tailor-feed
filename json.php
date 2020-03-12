<?php


error_reporting(0);
$key_not_set = !isset($_GET['secretkey']);
$user_not_set = !isset($_GET['user']);
if ($key_not_set || $user_not_set) {
    echo 'no key set or user';
    // header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
} else {
    define('CONFIG_PROTECTION', false);
}
$title = 'JSON RESULT';
require_once __DIR__ . '/config.php';
$key_not_match = $_GET['json_secret'] != $CFG->json_private;
if ($key_not_match) {
    echo 'no key match';
    // header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
echo 'yes';
header('Content-Type: application/json');
$user = new User($_GET['user']);
$page = $_GET['page'] ?? 1;
$data = $user->getArticlesJSON($page);
echo ($data);
