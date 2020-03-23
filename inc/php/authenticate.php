<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}

if (!$_SESSION['signedIn']) {
    session_unset();
    redirectGuestToLogin();
}

if (!isset($_SESSION['userName'])) {
    redirectGuestToLogin();
}

$CURRENTUSER = new User($_SESSION['userName']);
