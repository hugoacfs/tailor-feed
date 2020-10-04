<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
function displayHomeLink($pageId)
{
    $status = '';
    if ($pageId === 'home') $status = 'active';
    echo '<li title="Home link." class="nav-item ">
                <a class="nav nav-link ' . $status . ' ml-auto" href="index.php">
                    <i class="fas fa-home">
                    </i> Home
                </a>
            </li>';
}
function displayPagesBtn()
{
    echo '<button id="pages-btn" type="button" class="pages-btn btn btn-outline-light mr-1 ml-1" data-toggle="modal" data-target="#pagesModal">
            <span class="fas fa-at menu-fa"></span> 
            <span class="preferences-btn-text">Following</span>
          </button>';
}
function displayTopicsBtn()
{
    echo '<button id="topics-btn" type="button" class="topics-btn btn btn-outline-light mr-1 ml-1" data-toggle="modal" data-target="#topicsModal">
            <span class="menu-fa fas fa-hashtag"></span> 
            <span class="preferences-btn-text">Topics</span>
          </button>';
}
function displayMyFeedLink($pageId)
{
    $status = 'disabled';
    $user = '';
    if ($_SESSION['signedIn']) {
        $status = '';
        $user = $_SESSION['givenName'] . '\'s ' ?? 'My ';
    }
    if ($pageId === 'feed') $status = 'active';
    echo '<li class="nav-item ">
                <a class="nav-link ' . $status . '" href="feed.php"><i class="fas fa-stream">
                    </i> ' . $user . 'Feed
                </a>
            </li>';
}
function displayAdminLink($pageId)
{
    $status = '';
    if (!$_SESSION['signedIn']) $status = 'disabled';
    elseif ($pageId === 'admin') $status = 'active';

    echo '<li class="nav-item ">
                <a class="nav-link ' . $status . '" href="admin.php"><i class="fas fa-users-cog">
                    </i> Admin
                </a>
            </li>';
}
function displayLoginLink($pageId)
{
    $url = 'login.php';
    $text = 'Login';
    $status = '';
    if ($pageId === 'login') $status = 'active';
    if ($_SESSION['signedIn']) {
        $url = 'logout.php';
        if (isset($_SESSION['logout_url'])) $url = $_SESSION['logout_url']; //SSAML STUFF
        $text = 'Logout';
    }
    echo '<li class="nav-item ' . $status . '">
                <a class="nav-link " href="' . $url . '"><i class="fas fa-sign-out-alt">
                    </i> ' . $text . '
                </a>
            </li>';
}
function displayAboutLink($pageId)
{
    $status = '';
    if ($pageId === 'about') $status = 'active';
    $url = 'about.php';
    $text = 'About';
    echo '<li class="nav-item ">
                <a class="nav-link ' . $status . '" href="' . $url . '"><i class="fas fa-info-circle">
                    </i> ' . $text . '
                </a>
            </li>';
}
function displayFeedbackLink($pageId)
{
    $status = '';
    if ($_SESSION['signedIn']) {
        if ($pageId === 'feedback') $status = 'active';
        $url = 'feedback.php';
        $text = 'Feedback';
        echo '<li class="nav-item ">
                <a class="nav-link ' . $status . '" href="' . $url . '"><i class="fas fa-comments">
                    </i> ' . $text . '
                </a>
            </li>';
    }
}
?>

<body data-pageid="<?php echo $pageId; ?>" class="no-gutters pb-0 overflow-hidden">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark justify-content-end mynav">
        <?php
        $homeLink = $CFG->dirroot . '/index.php';
        if ($_SESSION['signedIn']) $homeLink = $CFG->dirroot . '/feed.php';
        ?>
        <a class="navbar-brand mr-auto" href="<?php echo $homeLink; ?>">
            <img class="navbar-logo" src="img/nav_logo.png" alt="University of Chichester News">
            <span class="nav-title pr-2">thefeed </span>
        </a>
        <?php
        if ($_SESSION['signedIn'] && $pageId === 'feed') {
            displayPagesBtn();
            displayTopicsBtn();
        }
        ?>
        <button class="navbar-toggler btn btn-outline-light mr-1 ml-1 align-items-center" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="menu-fa burger fas fa-bars align-items-center"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav ml-auto">
                <?php
                displayHomeLink($pageId);
                displayMyFeedLink($pageId);
                if ($_SESSION['isAdmin']) {
                    displayAdminLink($pageId);
                }
                displayAboutLink($pageId); //disabled until needed
                // displayFeedbackLink($pageId); //disabled
                displayLoginLink($pageId);
                ?>
            </ul>
        </div>
    </nav>