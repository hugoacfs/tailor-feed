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
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        var username = 'default';
        var safelock = 'false';
        // on scroll to bottom event
        var runscroll = true;
        var page = 1;
        window.onscroll = function(ev) {
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

        };
    });
</script>
</body>

</html>