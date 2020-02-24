<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
if (!isLoggedIn()) {
    $loginArray = signMeIn($CFG->authmethod);
    $_SESSION['username'] = $loginArray[0];
    $_SESSION['givenname'] = $loginArray[1];
    if (!doesUserExist($_SESSION['username'])) {
        buildUserProfile($_SESSION['username'], $_SESSION['givenname']);
        $_SESSION['welcomemessage'] = true;
        $_SESSION['wmtimestamp'] = time();
    }
    $_SESSION['userid'] = getUserId($_SESSION['username']);
    if (isUserAdmin($_SESSION['username'])) {
        $_SESSION['role'] = 'a';
    } else {
        $_SESSION['role'] = 'u';
    }
    $_SESSION['currentuser'] = new User($_SESSION['username']);
}
