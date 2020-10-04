<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
function displayReturnLink($pageId)
{
    echo '<li title="Home link." class="nav-item ">
                <a class="nav nav-link active ml-auto" href="../index.php">
                    <i class="fas fa-home">
                    </i> Home
                </a>
            </li>';
}
?>

<body data-pageid="<?php echo $pageId; ?>" class="no-gutters pb-0 overflow-hidden">
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark justify-content-end mynav">
        <?php
        $homeLink = $CFG->dirroot . '/index.php';
        if ($_SESSION['signedIn']) $homeLink = $CFG->dirroot . '/feed.php';
        ?>
        <a class="navbar-brand mr-auto" href="<?php echo $homeLink; ?>">
            <img class="navbar-logo" src="../img/nav_logo.png" alt="Logo">
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
                displayReturnLink($pageId);

                ?>
            </ul>
        </div>
    </nav>