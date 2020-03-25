<?php
define('CONFIG_PROTECTION', false);
$title = 'Login';
$pageId = 'login';
require_once(__DIR__ . '/config.php');
session_start();
require_once($CFG->dirroot . '/inc/html/head.php');
require_once($CFG->dirroot . '/inc/html/nav.php');
$isLoggedIn = $_SESSION['signedIn'] ?? false;
if (!$isLoggedIn) {
    if ($CFG->auth_method === 'SSAML') $_SESSION['USER'] = new SSAML();
    else {
        if ($_POST) {
            try {
                // $mode = isset($_POST['newaccount']) ?? false;
                if (isset($_POST['newaccount'])) $mode = true;
                else $mode = false;
                if ($mode) $givenname = $_POST['givenname'];
                else $givenname = '';
                $_SESSION['USER'] = new Authenticate($_POST['username'], $_POST['password'], $givenname, $mode);
            } catch (Exception $ex) {
                handleException($ex);
            }
        }
    }
    if (isset($_SESSION['USER'])) {
        $_SESSION['signedIn'] = $_SESSION['USER']->getSignedIn() ?? null;
        $_SESSION['givenName'] = $_SESSION['USER']->getGivenName() ?? null;
        $_SESSION['role'] = $_SESSION['USER']->getRole() ?? null;
        $_SESSION['userId'] = $_SESSION['USER']->getUserId() ?? null;
        $_SESSION['userName'] = $_SESSION['USER']->getUserName() ?? null;
        $_SESSION['currentUser'] = new User($_SESSION['userName']);
    }
}
if (!isset($_SESSION['signedIn'])) {
    if ($CFG->auth_method === 'SSAML') {
        header('Location: timeline.php');
        exit();
    } else {
        echo '<div class="text-center my-auto pt-5">
        <form class="form-signin pt-5" action="login.php" method="POST">
            <img class="login-logo mb-4" src="img/nav_logo.png" alt="">
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="" autofocus="">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
            <div id="name-input" style="display:none">
                    <input type="text" name="givenname" id="givenname" class="form-control" placeholder="First Name">
            </div>
            <div class="checkbox mb-3">
                <input class="form-check-input" type="checkbox" name="newaccount" value="on" id="newaccount">
                <label class="form-check-label" for="newaccount">
                    Create New Account
                </label>
            </div>
            <input class="btn btn-lg btn-primary btn-block" value="Login" type="submit" />
            <p class="mt-5 mb-3 text-muted"><a href="https://github.com/hugoacfs/tailor-feed" target="github">Find us on Github</a></p>
        </form>
    
    </div>
    </body>';
    };
} elseif (!$_SESSION['signedIn']) {
    session_unset();
    if ($CFG->auth_method === 'SSAML') $_SESSION['USER'] = new SSAML();
    redirectGuestToLogin();
} else redirectUserToTimeline();

require_once('inc/html/footer.php');