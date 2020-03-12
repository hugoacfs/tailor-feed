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
if (!isset($_POST['page'])) {
    echo 'page not set';
    exit;
} else {
    $page = intval($_POST['page']);
    unset($_POST['page']);
}
if (isset($_POST['username'])) $user = new User($_POST['username']);;
if (!isset($user)) {
    echo 'No user is set.';
    exit;
}
$newfeed = $user->displaySubscribedArticles($page);
echo $newfeed;
