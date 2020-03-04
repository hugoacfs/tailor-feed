<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
class Cron
{
    public $startTime;
    public $finishTime;
    public $type;

    public function __construct(string $type)
    {
        global $CFG;
        $this->type = $type;
        $this->startTime = time();
        echo "Initiating Cron Job for " . ucfirst($type) . "... \n";
        echo "Server Time: " . date('r', $this->startTime) . "\n";
        switch ($this->type) {
            case 'twitter':
                $config = $CFG->sources['twitter'];
                continue;
            case 'facebook':
                $config = $CFG->sources['facebook'];
                continue;
            case 'rss':
                $config = $CFG->sources['rss'];
                continue;
        }
        $update_articles = $config['update_articles']; //if true, then get articles
        $update_sources = $config['update_sources']; //if true then get sources
        $sources_cron = intval($config['cron']); //cron interval
        $sources_last_cron = intval($config['last_cron']); //last time run
        $run_now = $this->timeToRun($sources_cron, $sources_last_cron, time());
        if ($run_now) {
            if ($update_articles) {
                $this->updateArticles();
            }
            if ($update_sources) {
                $this->updateSources();
            }
            // UPDATE LAST RUN BY TYPE
            $this->updateLastRun();
        }
        $articles_cron = intval($CFG->articles_recycle_cron); //cron interval
        $articles_last_cron = intval($CFG->articles_last_cron); //last time run
        $articles_run_now = $this->timeToRun($articles_cron, $articles_last_cron, time());
        if ($CFG->articles_recycle_mode === 'on' && $articles_run_now) {
            echo 'Deleting old articles...';
            $deleteFrom = time() - $CFG->articles_recycle_interval;
            $removalStatus = $this->removeOldArticles($deleteFrom);
        }
        // END CRON
        $this->finishTime = time();
        echo "Ending job for " . $this->type . " sources" . "\n";
        echo "Server Time: " . date('r', $this->finishTime) . "\n";
        $jobDuration = $this->finishTime - $this->startTime;
        echo "Duration = " . $jobDuration . " seconds.\n";
    }

    private function timeToRun(int $cron, int $last_cron, int $now): bool
    {
        $time_to_run = $last_cron + $cron;
        return ($time_to_run <= $now);
    }
    private function updateArticles()
    {
        switch ($this->type) {
            case 'twitter':
                $this->updateTwitterArticles();
                continue;
            case 'facebook':
                $this->updateFacebookArticles();
                continue;
            case 'rss':
                $this->updateRssArticles();
                continue;
        }
    }
    private function updateTwitterArticles()
    {
        echo "Instanciating Twitter objs... \n";
        $sources = Twitter::getAllSources('twitter');
        //Tweet publisher
        foreach ($sources as $source) {
            echo ('Building articles for ' . $source->getReference() . " object... \n");
            $source->buildArticles();
            echo ('Publishing articles for ' . $source->getReference() . " object... \n");
            Article::publishArticles($source->getArticles());
        }
    }
    private function updateFacebookArticles()
    {
        echo "Instanciating Facebook objs... \n";
        echo "This method is not yet supported - aborting... \n";
    }
    private function updateRssArticles()
    {
        echo "Instanciating RSS objs... \n";
        echo "This method is not yet supported - aborting... \n";
    }
    private function updateSources()
    {
        switch ($this->type) {
            case 'twitter':
                $this->updateTwitterSources();
                continue;
            case 'facebook':
                $this->updateFacebookSources();
                continue;
            case 'rss':
                $this->updateRssSources();
                continue;
        }
    }
    private function updateTwitterSources()
    {
        echo ('Updating all Twitter sources details... ' . "\n");
        Source::updateAllSourcesDetails();
        echo ('Done! ' . "\n");
    }
    private function updateFacebookSources()
    {
        echo 'Updating all Facebook sources details... ' . "\n";
        echo "This method is not yet supported - aborting... \n";
    }
    private function updateRssSources()
    {
        echo 'Updating all Rss sources details... ' . "\n";
        echo "This method is not yet supported - aborting... \n";
    }
    private function updateLastRun()
    {
        global $DB;
        echo 'Updating last run time -> ' . $this->type . "\n";
        $DB->updateLastCronTime($this->type);
    }
    private function removeOldArticles($since): bool
    {
        global $DB;
        return $DB->deleteArticlesOlderThan($since);
    }
}
