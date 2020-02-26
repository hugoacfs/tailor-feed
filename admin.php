<?php
define('CONFIG_PROTECTION', false);
define('DESKTOP_VIEW', true);
$title = 'Admin Menu';
$pageId = 'admin';
require_once __DIR__ . '/config.php';
session_start();
require $CFG->dirroot . '/inc/php/authenticate.php';
require $CFG->dirroot . '/inc/html/head.php';
require $CFG->dirroot . '/inc/html/nav.php';
if (!isAdminLoggedIn()) {
    redirectUserToTimeline();
}
$table = $_GET['table'] ?? null;
if ($table) {
    $action = $_POST['action'] ?? null;
    unset($_POST['action']);
    if ($action) {
        $actionArray = $_POST;
        unset($_POST);
        $adminId = $_SESSION['userid'];
        $taskSuccess = performAdminTask($action, $actionArray, $adminId) ?? null;
        $message = '';
        if ($action === 'add-source') {
            $message = 'added a new source.';
        } elseif ($action === 'add-topic') {
            $message = 'added a new topic.';
        } elseif ($action === 'update-source') {
            $message = 'updated the source`s details.';
        } elseif ($action === 'update-topic') {
            $message = 'updated the topic`s details.';
        } elseif ($action === 'suspend-source' || $action === 'activate-source') {
            $message = 'updated the source status.';
        }elseif ($action === 'suspend-topic' || $action === 'activate-topic') {
            $message = 'updated the topic status.';
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
<script>
    //prevents form resubmission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
<div id="wrapper" class="container-fluid ">
    <div class="spacer d-flex justify-content-center align-items-center">
    </div>
    <div class="row ">
        <div class="col-2 p-0">
            <!-- sidenav -->
            <nav id="admin-nav" class="navbar navbar-expand-md bg-dark breadcrumb align-content-start">
                <ul class="navbar-nav flex-column ">
                    <li class="nav-item">
                        <a href="admin.php" class="nav nav-link text-uppercase text-light">Admin Menu</a>
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
                        if ($table === 'sources') {
                            echo '<i class="fas fa-arrow-left"></i>';
                        }
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
                            if ($table === 'topics') {
                                echo '<i class="fas fa-arrow-left"></i>';
                            }
                            ?>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="col-10 ">
            <?php
            if ($table === 'sources') {
                require $CFG->dirroot . '/inc/html/admin/sources.php';
            } elseif ($table === 'topics') {
                require $CFG->dirroot . '/inc/html/admin/topics.php';
            } else {
                require $CFG->dirroot . '/inc/html/admin/home.php';
            }
            ?>
        </div>
    </div>
</div>
<?php
require $CFG->dirroot . '/inc/html/admin/modal.php';
?>
</body>

</html>