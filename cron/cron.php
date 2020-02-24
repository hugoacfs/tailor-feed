<?php
if (php_sapi_name() != 'cli') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
define('CONFIG_PROTECTION', false);
require __DIR__ .'/../config.php';
echo "Starting ".__DIR__."/cron.php \n";
$cronStartTime = time();
echo "Server Time: ".date( 'r',$cronStartTime)."\n";
echo "Instanciating Twitter objs... \n";
$sources = Twitter::getAllSources('twitter');
//Tweet publisher
foreach ($sources as $source) {
    echo('Building articles for '.$source->getReference()." object... \n");
    $source->buildArticles();
    echo('Publishing articles for '.$source->getReference(). " object... \n");
    Article::publishArticles($source->getArticles());
}
// Set to true to update the Name and Image path of all Sources in DB
$updateSourcesDetails = false;
if($updateSourcesDetails){
    $cron2ndStartTime = time();
    echo('Updating all sources details... '."\n");
    Source::updateAllSourcesDetails();
    echo('Done! '."\n");
}
$cronEndTime = time();
echo "Cron job finished at: ".date( 'r',$cronEndTime)."\n";
$jobDuration = $cronEndTime - $cronStartTime;
echo "Duration = ". $jobDuration ." seconds.\n";
