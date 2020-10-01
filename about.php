<?php
define('CONFIG_PROTECTION', false);
$title = 'About Community News';
$pageId = 'about';
require_once(__DIR__ . '/config.php');
session_start();
require_once($CFG->dirroot . '/inc/html/head.php');
require_once($CFG->dirroot . '/inc/html/nav.php');
?>
<div class="row ">
    <?php
    require_once($CFG->dirroot . '/inc/html/toast-message.php');
    ?>

    <div class="container-fluid" style="max-width: 1200px;">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 p-5">
                    <div class="jumbotron">
                        <h1>
                            <i class="fas fa-stream pr-2"></i> <span style="font-family: 'Baloo 2', cursive;">thefeed</span>
                        </h1>

                        <div>
                            <p>
                                The university is continually striving to make news and updates as useful and informative as possible. Sometimes this can feel like informational overload, with the inbox continuously pinging with the latest update, that may, or may not, be relevant to your needs or interests.
                            </p>
                        </div>
                        <div>
                            <p>
                                <strong><span style="font-family: 'Baloo 2', cursive;">thefeed</span></strong> is an attempt at allowing you more control over the information that you do see. In this first iteration we have provided a curated list of university Twitter accounts that you can follow.
                            </p>
                        </div>
                        <div>
                            <p>
                                You can follow as many or as few accounts as you like. If you want to just follow topics, we have provided a list of topics that you can follow.
                            </p>
                        </div>
                    </div>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-6">
                                <h2>
                                    Isn’t this just like Twitter?
                                </h2>
                                <p>
                                    At the moment, it may seem that way. The plan is to integrate other sources of news and information into a single news feed. For example, we’re looking at including news from Moodle.
                                </p>
                                <p>
                                    The hope is we can pull as much university news into a single place where you are in control of what appears on your timeline; freeing up your inbox for only essential information.
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h2>
                                    The future
                                </h2>
                                <p>
                                    We have a number of plans for improving this service; if you have any suggestions we would love to hear from you.
                                </p>
                                <p>
                                    The team <i class="fas fa-arrow-right"></i>
                                    <a class="" href="mailto:thefeed@chi.ac.uk">thefeed@chi.ac.uk</a>
                                </p>
                                <p class="text-center">
                                    <img class="" src="img/brand_banner.png" alt="University of Chichester News">
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<?php require_once('inc/html/footer.php');?>
</html>