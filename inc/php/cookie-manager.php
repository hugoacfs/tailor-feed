<?php
session_start();
if (!isset($_SESSION['signedIn'])) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
$forbid = $_POST['safelock'] ?? 'true';
if ($forbid === 'true') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
} else {
    define('CONFIG_PROTECTION', false);
}
require_once(__DIR__ . '/../../config.php');
if (isset($_POST)) $str = print_r($_POST);
if (isset($_COOKIE)) $str = print_r($_COOKIE);
if (!isset($_POST['userid'])) {
    echo 'Tailor-feed error: No userid defined for cookie manager.';
    exit;
}
if (!isset($_POST['action'])) {
    echo 'Tailor-feed error: No action defined for cookie manager.';
    exit;
}
if (!isset($_POST['cookiename'])) {
    echo 'Tailor-feed error: No cookiename defined for cookie manager.';
    exit;
}
$action = $_POST['action'];
$userId = $_POST['userid'];
$cookieName = $_POST['cookiename'];
switch ($action) {
    case 'delete':
        $bread = unserialize($_COOKIE[$userId]);
        unset($bread[$cookieName]); //remove message
        setcookie($userId, serialize($bread), 0, '/'); //resets cookie

}
