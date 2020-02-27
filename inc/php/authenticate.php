<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
$isLoggedIn = $_SESSION['signedIn'] ?? false;
$_SESSION['givenName'];
$_SESSION['role'];
$_SESSION['userId'];
$_SESSION['userName'];
if (!$isLoggedIn) {
    $loginArray = signMeIn($CFG->authmethod);
    if ($CFG->authmethod === 'SSAML') {
        $_SESSION['USER'] = new SSAML();
    } else {
        $loginArray = Authenticate::requestUserInput();
        $_SESSION['USER'] = new Authenticate();
    }
    $_SESSION['signedIn'] = $_SESSION['USER']->signedIn ?? false;
    $_SESSION['givenName'] = $_SESSION['USER']->givenName ?? null;
    $_SESSION['role'] = $_SESSION['USER']->role ?? null;
    $_SESSION['userId'] = $_SESSION['USER']->userId ?? null;
    $_SESSION['userName'] = $_SESSION['USER']->userName ?? null;
    $_SESSION['currentUser'] = new User($_SESSION['userName']);
}
