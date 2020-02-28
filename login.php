<?php
define('CONFIG_PROTECTION', false);
$title = 'Login';
$pageId = 'login';
require_once __DIR__ . '/config.php';
session_start();
require $CFG->dirroot . '/inc/html/head.php';
require $CFG->dirroot . '/inc/html/nav.php';
$isLoggedIn = $_SESSION['signedIn'] ?? false;
if (!$isLoggedIn) {
    if ($CFG->auth_method === 'SSAML') {
        $_SESSION['USER'] = new SSAML();
    } else {
        if ($_POST) {
            $_SESSION['USER'] = new Authenticate($_POST['username'], $_POST['password']);
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
        //do simple saml stuff
        header('Location: timeline.php');
        die();
    } else {
        echo '<div class="text-center my-auto">
        <form class="form-signin" action="login.php" method="POST">
            <img class="mb-4" src="https://www.chi.ac.uk/sites/all/themes/chiuni_2016/logo.png" alt="">
            <h1 class="h3 mb-3 font-weight-normal">Login Form</h1>
            <label for="username" class="sr-only">Username</label>
            <input type="text" name="username" id="username" class="form-control" placeholder="Username" required="" autofocus="">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required="">
            <div class="checkbox mb-3">
            </div>
            <input class="btn btn-lg btn-primary btn-block" value="Login" type="submit" />
            <p class="mt-5 mb-3 text-muted">© 2020</p>
        </form>
    
    </div>
    </body>';
    };
} elseif (!$_SESSION['signedIn']) {
    session_unset();
    redirectGuestToLogin();
} else {
    redirectUserToTimeline();
}
