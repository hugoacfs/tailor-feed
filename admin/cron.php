<?php
define('CONFIG_PROTECTION', false);
require_once(__DIR__ . '/../config.php');
function renderCron(bool $html = false)
{
    $break = "\n";
    if ($html) {
        session_start();
        require_once('../inc/php/authenticate.php');
        require_once('head.php');
        require_once('nav.php');

        if (!isAdminLoggedIn()) {
            redirectUserToFeed();
            exit;
        }
        echo '<div class="row "><div class="container-fluid"><div class="spacer d-flex justify-content-center align-items-center">
        </div><pre class="bg-dark text-white px-4 mt-3 font-large rounded h5"><code>';
        $break = "<br>";
    }
    echo "Getting " . __DIR__ . "/cron.php ...$break";
    $cronStartTime = time();
    echo "Server Time: " . date('r', $cronStartTime) . $break;

    Twitter::cron();
    Cron::pruneArticles();
    Cron::pruneUsers();

    $cronEndTime = time();
    echo "All cron jobs finished at: " . date('r', $cronEndTime) . $break;
    $jobDuration = $cronEndTime - $cronStartTime;
    echo "Duration = " . $jobDuration . " seconds.$break";
    if ($html) {
        echo '</code></pre></div></div></div>';
    }
}
$cli = php_sapi_name() != 'cli';
renderCron($cli);
