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
    public $sourcesStatus = false;
    public $articlesStatus = false;
    public $articlesRecycleStatus = false;
    public $usersRecycleStatus = false;
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
                $this->articlesStatus = $this->updateArticles();
            }
            if ($update_sources) {
                $this->sourcesStatus = $this->updateSources();
            }
            // UPDATE LAST RUN BY TYPE
        }
        //TODO:FIX make into function
        $articles_cron = intval($CFG->articles_recycle_cron); //cron interval
        $articles_last_cron = intval($CFG->articles_recycle_last_cron); //last time run
        $articles_run_now = $this->timeToRun($articles_cron, $articles_last_cron, time());
        if ($CFG->articles_recycle_mode === 'on' && $articles_run_now) {
            echo 'Deleting old articles...';
            $deleteFrom = time() - $CFG->articles_recycle_interval;
            $this->articlesRecycleStatus = $this->removeOldArticles($deleteFrom);
        }
        $users_cron = intval($CFG->users_recycle_cron); //cron interval
        $users_last_cron = intval($CFG->users_recycle_last_cron); //last time run
        $users_run_now = $this->timeToRun($users_cron, $users_last_cron, time());
        if ($CFG->users_recycle_mode === 'on' && $users_run_now) {
            echo 'Deleting old articles...';
            $deleteFrom = time() - $CFG->users_recycle_interval;
            $this->usersRecycleStatus = $this->removeOldUsers($deleteFrom);
        }
        // END CRON
        $this->updateLastRun();
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
                return $this->updateTwitterArticles();
            case 'facebook':
                return $this->updateFacebookArticles();
            case 'rss':
                return $this->updateRssArticles();
        }
    }
    private function updateTwitterArticles(): bool
    {
        echo "Instanciating Twitter objs... \n";
        $sources = Twitter::getAllSources('twitter');
        //Tweet publisher
        foreach ($sources as $source) {
            echo ('Building articles for ' . $source->getReference() . " object... \n");
            $source->buildArticles();
            echo ('Publishing articles for ' . $source->getReference() . " object... \n");
            $success = Article::publishArticles($source->getArticles());
            if (!$success) return false;
        }
        return true;
    }
    private function updateFacebookArticles()
    {
        echo "Instanciating Facebook objs... \n";
        echo "This method is not yet supported - aborting... \n";
        return false;
    }
    private function updateRssArticles()
    {
        echo "Instanciating RSS objs... \n";
        echo "This method is not yet supported - aborting... \n";
        return false;
    }
    private function updateSources(): bool
    {
        switch ($this->type) {
            case 'twitter':
                return $this->updateTwitterSources();
            case 'facebook':
                return $this->updateFacebookSources();
            case 'rss':
                return $this->updateRssSources();
        }
    }
    private function updateTwitterSources(): bool
    {
        echo ('Updating all Twitter sources details... ' . "\n");
        return Source::updateAllSourcesDetails();
    }
    private function updateFacebookSources(): bool
    {
        echo 'Updating all Facebook sources details... ' . "\n";
        echo "This method is not yet supported - aborting... \n";
        return false;
    }
    private function updateRssSources(): bool
    {
        echo 'Updating all Rss sources details... ' . "\n";
        echo "This method is not yet supported - aborting... \n";
        return false;
    }
    private function updateLastRun()
    {
        global $DB;
        echo 'Updating last run time -> ' . $this->type . "\n";
        if ($this->sourcesStatus || $this->articlesStatus) $DB->updateLastSourcesCronTime($this->type);
        if ($this->articlesRecycleStatus) $DB->updateRecycleCronTime('articles');
        if ($this->usersRecycleStatus) $DB->updateRecycleCronTime('users');
    }
    private function removeOldArticles($since): bool
    {
        global $DB;
        return $DB->deleteArticlesOlderThan($since);
    }
    private function removeOldUsers($since): bool
    {
        global $DB;
        return $DB->deleteUsersOlderThan($since);
    }
}
