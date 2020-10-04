<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}

if (!$_SESSION['signedIn'] || !isset($_SESSION['userName'])) {
    session_unset();
    header('Location: login.php');
    exit;
}

$userName = $_SESSION['userName'] ?? '';
$CURRENTUSER = new User($userName);
