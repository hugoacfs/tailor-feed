<?php
error_reporting(0);
$key_not_set = !isset($_GET['secretkey']);
$user_not_set = !isset($_GET['user']);
if ($key_not_set || $user_not_set) {
    echo 'Permission denied. [null user] or [null key]';
    exit;
    die();
} else {
    define('CONFIG_PROTECTION', false);
}
$title = 'JSON RESULT';
require_once __DIR__ . '/config.php';
$key_not_match = $_GET['secretkey'] != $CFG->jsonkeys['secret_key'];
if ($key_not_match) {
    echo 'Permission denied. [invalid key]';
    exit;
    die();
}
session_start();
header('Content-Type: application/json');
$user = new User($_GET['user']);
$page = $_GET['page'] ?? 1;
$data = $user->getArticlesJSON($page);
echo ($data);
