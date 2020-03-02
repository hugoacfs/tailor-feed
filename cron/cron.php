<?php
if (php_sapi_name() != 'cli') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
define('CONFIG_PROTECTION', false);
require __DIR__ .'/../config.php';
echo "Getting ".__DIR__."/cron.php ...\n";
$cronStartTime = time();
echo "Server Time: ".date( 'r',$cronStartTime)."\n";

$twitter = new Cron('twitter');
$facebook = new Cron('facebook');
$rss = new Cron('rss');


$cronEndTime = time();
echo "All cron jobs finished at: ".date( 'r',$cronEndTime)."\n";
$jobDuration = $cronEndTime - $cronStartTime;
echo "Duration = ". $jobDuration ." seconds.\n";
