<?php
define('CONFIG_PROTECTION', false);
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
$table = $_GET['table'] ?? '';
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
            <nav id="admin-nav" class="navbar navbar-expand-md breadcrumb navbar-light align-content-start">
                <ul class="navbar-nav flex-column ">
                    <li class="nav-item">
                        <a class="nav nav-link text-uppercase text-muted">Admin Menu</a>
                    </li>
                    <li class="nav-item">

                        <?php
                        $url = 'admin.php?table=sources';
                        echo '<a href="' . $url . '" class="nav-link">';
                        ?>
                        <i class="fas fa-table" aria-hidden="true"></i>
                        Sources
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
                            Topics
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
                require $CFG->dirroot . '/inc/html/admin/sources-table.php';
            } elseif ($table === 'topics') {
                require $CFG->dirroot . '/inc/html/admin/topics-table.php';
            } else {
                require $CFG->dirroot . '/inc/html/admin/admin-home.php';
            }
            ?>
        </div>
    </div>
</div>
<?php
require $CFG->dirroot . '/inc/html/admin/modal-edit.php';
?>
</body>
</html>