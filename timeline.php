<?php
define('CONFIG_PROTECTION', false);
$title = 'Your Timeline';
$pageId = 'timeline';
require_once __DIR__ . '/config.php';
session_start();
require $CFG->dirroot . '/inc/php/authenticate.php';
require $CFG->dirroot . '/inc/html/head.php';
require $CFG->dirroot . '/inc/html/nav.php';
if (isset($_POST['submitpages'])) {
    unset($_POST['submitpages']);
    $sourcesIds = Source::getAllSourcesIds();
    $newSubscribeList = [];
    foreach ($sourcesIds as $id) {
        $idString = strval($id);
        $isInArray = in_array($idString, $_POST, true);
        if ($isInArray) $newSubscribeList[] = $id;
    }
    $CURRENTUSER->updatePreferences($newSubscribeList, 'source');
    $CURRENTUSER->updateUserSubcribedList();
    $CURRENTUSER = new User($_SESSION['userName']);
}
if (isset($_POST['submittopics'])) {
    unset($_POST['submittopics']);
    $topicsIds = Article::getAllTopicsIds();
    $newTopicsList = [];
    foreach ($topicsIds as $id) {
        $idString = strval($id);
        $isInArray = in_array($idString, $_POST, true);
        if ($isInArray) $newTopicsList[] = $id;
    }
    $CURRENTUSER->updatePreferences($newTopicsList, 'topic');
    $CURRENTUSER->updateUserTopicsList();
    $CURRENTUSER = new User($_SESSION['userName']);
}
?>
<div class="row ">
    <?php
    require $CFG->dirroot . '/inc/html/toast-message.php';
    ?>
    <div class="container-fluid">
        <div class="spacer d-flex justify-content-center align-items-center">
        </div>
        <div id='news-feed' class="container card">
            <!-- articles -->
        </div>
    </div>
    <div class="row mx-auto pt-3">
        <span class="load-feed-spinner spinner-border text-primary"></span>
    </div>
    <div style="display: none;">
        <i id="current-username"><?php echo $_SESSION['userName']; ?></i>
        <i id="current-safelock"><?php echo false; ?></i>
        <i id="current-page">1</i>
    </div>
</div>
<?php
$_POST = [];
include $CFG->dirroot . '/inc/html/modal-pages.php';
include $CFG->dirroot . '/inc/html/modal-topics.php';
require_once 'inc/html/footer.php'
?>
</body>

</html>