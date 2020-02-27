<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
$isLoggedIn = $_SESSION['signedIn'] ?? false;
if (!$isLoggedIn) {
    if ($CFG->authmethod === 'SSAML') {
        $_SESSION['USER'] = new SSAML();
    } else {
        $loginArray = Authenticate::requestUserInput();
        $_SESSION['USER'] = new Authenticate($loginArray['username'],$loginArray['password'],$loginArray['givenname']);
    }
    $_SESSION['signedIn'] = $_SESSION['USER']->getSignedIn() ?? false;
    $_SESSION['givenName'] = $_SESSION['USER']->getGivenName() ?? null;
    $_SESSION['role'] = $_SESSION['USER']->getRole() ?? null;
    $_SESSION['userId'] = $_SESSION['USER']->getUserId() ?? null;
    $_SESSION['userName'] = $_SESSION['USER']->getUserName() ?? null;
    $_SESSION['currentUser'] = new User($_SESSION['userName']);
}
