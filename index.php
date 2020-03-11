<?php
define('CONFIG_PROTECTION', false);
$title = 'Community News';
$pageId = 'home';
require __DIR__ . '/config.php';
session_start();
require $CFG->dirroot . '/inc/html/head.php';
require $CFG->dirroot . '/inc/html/nav.php';
?>
<div class="row ">
    <?php
    require $CFG->dirroot . '/inc/html/toast-message.php';
    ?>

    <div class="container-fluid">
        <div class="spacer d-flex justify-content-center align-items-center">
        </div>
        <div id='news-feed' class="container card">
            <?php
            $feed = (new User('default'))->displaySubscribedArticles();
            echo ($feed);
            ?>
        </div>
        <div style="display: none;">
            <i id="current-username"><?php echo 'default'; ?></i>
            <i id="current-safelock"><?php echo false; ?></i>
            <i id="current-page">1</i>
        </div>
    </div>
</div>
</div>
<?php require_once 'inc/html/footer.php' ?>
</body>

</html>