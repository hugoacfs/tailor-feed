<?php
session_start();
$forbid = $_POST['safelock'] ?? 'true';
$page = intval($_POST['page']) ?? 1;
unset($_POST['page']);
if (!isset($_SESSION['signedIn']) || !isset($_SESSION['userName']) || $forbid === 'true') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
define('CONFIG_PROTECTION', false);
require_once(__DIR__ . '/../../config.php');
$user = new User($_SESSION['userName']);;
$newfeed = $user->displaySubscribedArticles($page);
echo $newfeed;