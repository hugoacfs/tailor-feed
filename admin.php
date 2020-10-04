<?php
define('CONFIG_PROTECTION', false);
define('DESKTOP_VIEW', true);
$title = 'Admin Menu';
$pageId = 'admin';
require_once(__DIR__ . '/config.php');
session_start();
require_once($CFG->dirroot . '/inc/php/authenticate.php');
require_once($CFG->dirroot . '/inc/html/head.php');
require_once($CFG->dirroot . '/inc/html/nav.php');
if (!$_SESSION['isAdmin']) {
    header('Location: feed.php');
}
$adminCookie = $_COOKIE['adminMenu'] ?? false;
$adminMenu = strip_tags($adminCookie);
$adminState = 'show';
if ($adminMenu === 'closed') {
    $adminState = '';
}
if (!$_GET) {
    $adminState = 'show';
}
// print_r($_POST);
$table = $_GET['table'] ?? null;
$table = strip_tags($table);
if ($table) {
    $action = $_POST['action'] ?? null;
    if ($action) $action = strip_tags($action);
    unset($_POST['action']);
    if ($action) {
        foreach ($_POST as $k => $v) {
            $actionArray[$k] = strip_tags($v);
        }
        unset($_POST);
        $adminId = $_SESSION['userId'];
        $taskSuccess = performAdminTask($action, $actionArray, $adminId) ?? null;
        $message = '';
        switch ($action) {
            case 'add-source':
                $message = 'added a new source.';
                break;
            case 'add-topic':
                $message = 'added a new topic.';
                break;
            case 'update-source':
                $message = 'updated the source`s details.';
                break;
            case 'update-topic':
                $message = 'updated the topic`s details.';
                break;
            case 'suspend-source':
            case 'activate-source':
                $message = 'updated the source status.';
                break;
            case 'suspend-topic':
            case 'activate-topic':
                $message = 'updated the topic status.';
                break;
            case 'delete-article':
                $message = 'removed the article from the database.';
                break;
            case 'delete-topic':
                $message = 'removed the topic from the database.';
                break;
            case 'update-config':
                $message = 'updated the configuration settings.';
                break;
            default:
                break;
        }
        $title = 'success';
        $status = 'You successfully';
        if (!$taskSuccess) {
            $title = 'warning';
            $status = 'Something went wrong when performing the previous task.';
            $message = '';
        }
        echo '
                <div class="fixed-bottom mx-auto mb-2">
                    <div class="alert alert-' . $title . ' alert-dismissible fade show" role="alert">
                    <strong>' . ucfirst($title) . '!</strong> ' . $status . ' ' . $message . '
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                    </div>
                </div>';
    }
}
unset($_POST);
?>
<div id="wrapper" class="container-fluid bg-darker h-100" style="height: 100vh !important;">
    <div class="spacer d-flex justify-content-center align-items-center">
    </div>
    <div class="row ">
        <div class="col-2 p-0">
            <a class="btn btn-secondary w-100 mb-1 mt-1" onclick="toggleMenu()" data-toggle="collapse" href="#admin-sidebar" role="button" aria-expanded="false" aria-controls="admin-sidebar">
                <i class="fas fa-caret-down"></i>
                Toggle Side Nav
            </a>
        </div>
    </div>
    <div class="row ">
        <div id="admin-sidebar" class="col-2 p-0 collapse <?php echo $adminState; ?>">
            <!-- sidenav -->
            <nav id="admin-nav" class="navbar navbar-expand-md bg-dark breadcrumb align-content-start">
                <ul class="navbar-nav flex-column ">
                    <li class="nav-item">
                        <a href="admin.php" class="nav nav-link text-uppercase text-light">Admin Menu</a>
                    </li>
                    <li class="nav-item">
                        <a href="?table=articles" class="nav-link">
                            <i class="fas fa-table" aria-hidden="true"></i>
                            <span class="text-light">
                                Articles
                            </span>
                            <?php
                            if ($table === 'articles') echo '<i class="fas fa-arrow-left"></i>';
                            ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <?php
                        $url = 'admin.php?table=sources';
                        echo '<a href="' . $url . '" class="nav-link">';
                        ?>
                        <i class="fas fa-table" aria-hidden="true"></i>
                        <span class="text-light">
                            Sources
                        </span>
                        <?php
                        if ($table === 'sources') echo '<i class="fas fa-arrow-left"></i>';

                        ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?table=topics" class="nav-link">
                            <i class="fas fa-table" aria-hidden="true"></i>
                            <span class="text-light">
                                Topics
                            </span>
                            <?php
                            if ($table === 'topics') echo '<i class="fas fa-arrow-left"></i>';
                            ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="?table=settings" class="nav-link">
                            <i class="fas fa-cog" aria-hidden="true"></i>
                            <span class="text-light">
                                Settings
                            </span>
                            <?php
                            if ($table === 'settings') echo '<i class="fas fa-arrow-left"></i>';
                            ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo './admin/cron.php'; ?>" class="nav-link">
                            <i class="fas fa-cog" aria-hidden="true"></i>
                            <span class="text-light">
                                Run Cron Now
                            </span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col ">
            <?php
            switch ($table) {
                case 'sources':
                    require_once($CFG->dirroot . '/inc/html/admin/sources.php');
                    break;
                case 'topics':
                    require_once($CFG->dirroot . '/inc/html/admin/topics.php');
                    break;
                case 'articles':
                    require_once($CFG->dirroot . '/inc/html/admin/articles.php');
                    break;
                case 'settings':
                    require_once($CFG->dirroot . '/inc/html/admin/settings.php');
                    break;
                default:
                    require_once($CFG->dirroot . '/inc/html/admin/home.php');
                    break;
            }
            ?>
        </div>
    </div>
</div>
<?php
require_once($CFG->dirroot . '/inc/html/admin/modal.php');
require_once('inc/html/footer.php');
?>
</body>

</html>