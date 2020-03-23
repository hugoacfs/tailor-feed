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
require_once __DIR__ . '/../../config.php';
if (isset($_POST)) $str = print_r($_POST);
if (isset($_COOKIE)) $str = print_r($_COOKIE);
if (!isset($_POST['userid'])) {
    echo 'Tailor-feed error: No userid defined for cookie manager.';
    handleException(new Exception('Tailor-feed error: No userid defined for cookie manager.', 601));
    exit;
}
if (!isset($_POST['action'])) {
    echo 'Tailor-feed error: No action defined for cookie manager.';
    handleException(new Exception('Tailor-feed error: No action defined for cookie manager.', 602));
    exit;
}
if (!isset($_POST['cookiename'])) {
    echo 'Tailor-feed error: No cookiename defined for cookie manager.';
    handleException(new Exception('Tailor-feed error: No cookiename defined for cookie manager.', 603));
    exit;
}
$action = $_POST['action'];
$userId = $_POST['userid'];
$cookieName = $_POST['cookiename'];
echo 'yeah!' . $userId;
switch ($action) {
    case 'delete':
        $print = '';
        $print .= '<pre>';
        $print .= print_r(unserialize($_COOKIE[$userId]));
        $print .= '</pre>';
        // echo $print;
        $bread = unserialize($_COOKIE[$userId]);
        unset($bread[$cookieName]); //remove message
        setcookie($userId, serialize($bread), 0, '/'); //resets cookie

        $print .= '<pre>';
        $print .= print_r($bread);
        $print .= '</pre>';
        echo $print;
}
// echo 'done cookie:'.$cookieName;
