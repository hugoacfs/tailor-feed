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
            <?php
            $endMessage = '<div id="end-news" class="card-body ">
                                <h4 class="card-title">
                                    <a class=" card-link">
                                        That\'s all for now...
                                    </a>
                                </h4>
                                <p class="card-text">To see more university news, login with your university account.
                                    <button onclick="location.href=\'login.php\'" class="btn btn-dark btn-outline-light mr-1 ml-1 border">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true">
                                        </i> Login
                                    </button>
                                </p>
                                <span style="display: none;">newscode:340</span>
                            </div>';
            if (isset($_SESSION['USER'])) {
                $endMessage = '<div id="end-news" class="card-body ">
                                    <h4 class="card-title">
                                        <a class=" card-link">
                                            That\'s all for now...
                                        </a>
                                    </h4>
                                    <p class="card-text">To see more university news, go to your timeline.
                                        <button onclick="location.href=\'timeline.php\'" class="btn btn-dark btn-outline-light mr-1 ml-1 border">
                                            <i class="fas fa-stream" aria-hidden="true">
                                            </i> My Timeline
                                        </button>
                                    </p>
                                    <span style="display: none;">newscode:340</span>
                                </div>';
            }
            echo $endMessage;
            ?>
        </div>
    </div>
    <div class="row mx-auto pt-3">
        <span class="load-feed-spinner spinner-border text-primary hide-input"></span>
    </div>
</div>
</div>
<?php
include $CFG->dirroot . '/inc/html/modal-images.php';
require_once 'inc/html/footer.php';
?>
</body>

</html>