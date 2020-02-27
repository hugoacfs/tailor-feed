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
    $newSubscribeList = array();
    foreach ($sourcesIds as $id) {
        $string = strval($id);
        $isInArray = in_array($string, $_POST, true);
        if ($isInArray) {
            $newSubscribeList[] = $id;
        }
    }
    $_SESSION['currentUser']->updatePreferences($newSubscribeList, 'source');
    $_SESSION['currentUser']->updateUserSubcribedList();
    $_SESSION['currentUser'] = new User($_SESSION['userName']);
} else {
    unset($_POST['submitpages']);
}

if (isset($_POST['submittopics'])) {
    unset($_POST['submittopics']);
    $topicsIds = Article::getAllTopicsIds();
    $newTopicsList = array();
    foreach ($topicsIds as $id) {
        $string = strval($id);
        $isInArray = in_array($string, $_POST, true);
        if ($isInArray) {
            $newTopicsList[] = $id;
        }
    }
    $_SESSION['currentUser']->updatePreferences($newTopicsList, 'topic');
    $_SESSION['currentUser']->updateUserTopicsList();
    $_SESSION['currentUser'] = new User($_SESSION['userName']);
} else {
    unset($_POST['submittopics']);
}
if (!isLoggedIn()) {
    redirectGuestToLogin();
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
            <?php
            $feed = $_SESSION['currentUser']->displaySubscribedArticles();
            echo ($feed);
            ?>
        </div>
    </div>
    <div class="row mx-auto pt-3">
        <span class="spinner-border text-primary"></span>
    </div>
</div>

<?php
$_POST = array();
include $CFG->dirroot . '/inc/html/modal-pages.php';
include $CFG->dirroot . '/inc/html/modal-topics.php';
?>
</body>
<script>
    // prevents form re-submission
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    // ajax for pages
    $(document).ready(function() {
        var username = '<?php echo $_SESSION['userName']; ?>';
        var safelock = '<?php echo false; ?>';
        var loadingspinner = '<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>'
        // Pages Hide Modal
        $("#pagesModal").on("hidden.bs.modal", _.debounce(function() {
            $("#pages-modal-form").html('');
            document.getElementById('search-area-pages').value = "";
            $("#pages-modal-form").html(loadingspinner);
        }), 150, {
            leading: true
        });
        // Topics Hide Modal
        $("#topicsModal").on("hidden.bs.modal", _.debounce(function() {
            $("#topics-modal-form").html('');
            document.getElementById('search-area-topics').value = "";
            $("#topics-modal-form").html(loadingspinner);
        }), 150, {
            leading: true
        });
        // Pages AJAX
        $("#pages-btn").on("click", _.debounce(function() {
            ajaxPages = $.ajax({
                url: "inc/php/load-pages.php",
                type: 'POST',
                data: {
                    username: username,
                    safelock: safelock
                },
            });
            ajaxPages.done(function(response, textStatus, jqXHR) {
                $("#pages-modal-form").html('');
                $("#pages-modal-form").html(response);
                $("[data-toggle='toggle']").bootstrapToggle('destroy')
                $("[data-toggle='toggle']").bootstrapToggle();
            });
        }, 1500, {
            leading: true
        }));
        // Topics AJAX
        $("#topics-btn").on("click", _.debounce(function() {
            ajaxPages = $.ajax({
                url: "inc/php/load-topics.php",
                type: 'POST',
                data: {
                    username: username,
                    safelock: safelock
                },
            });
            ajaxPages.done(function(response, textStatus, jqXHR) {
                $("#topics-modal-form").html('');
                $("#topics-modal-form").html(response);
                $("[data-toggle='toggle']").bootstrapToggle('destroy')
                $("[data-toggle='toggle']").bootstrapToggle();
            });
        }, 1500, {
            leading: true
        }));
        // on scroll to bottom event
        var runscroll = true;
        var page = 1;
        window.onscroll = _.debounce(function(ev) {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                page = parseInt(page) + 1;
                if (runscroll) {
                    ajaxFeed = $.ajax({
                        url: "inc/php/load-feed.php",
                        type: 'POST',
                        data: {
                            username: username,
                            page: page,
                            safelock: safelock
                        },
                    });
                    ajaxFeed.done(function(response, textStatus, jqXHR) {
                        // code 340 means nothing to show
                        if (~response.indexOf("newscode:340")) {
                            runscroll = false;
                            if ($('#end-news').length === 0) {
                                $("#news-feed").append(response);
                            }
                        } else {
                            $("#news-feed").append(response);
                        }
                    });
                }
            }

        }, 500, {
            leading: true
        });
    });
</script>

</html>