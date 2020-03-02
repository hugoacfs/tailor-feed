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
        // print_r('<pre>');
        // print_r($CFG);
        // print_r('</pre>');
        $this->startTime = time();
        echo "Initiating Cron Job for " . ucfirst($type) . "... \n";
        echo "Server Time: " . date('r', $this->startTime) . "\n";
        if ($type === 'twitter') {
            $config = $CFG->sources['twitter'];
        } elseif ($type === 'facebook') {
            $config = $CFG->sources['facebook'];
        } elseif ($type === 'rss') {
            $config = $CFG->sources['rss'];
        }
        $update_articles = $config['update_articles']; //if true, then get articles
        $update_sources = $config['update_sources']; //if true then get sources
        $cron = intval($config['cron']); //cron interval
        $last_cron = intval($config['last_cron']); //last time run
        $now = time();
        $time_to_run = $last_cron + $cron;
        $run_now = false;
        if ($time_to_run <= $now) {
            $run_now = true;
        }
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
        $this->finishTime = time();
        echo "Ending job for " . $this->type . " sources" . "\n";
        echo "Server Time: " . date('r', $this->finishTime) . "\n";
        $jobDuration = $this->finishTime - $this->startTime;
        echo "Duration = " . $jobDuration . " seconds.\n";
    }
    private function updateArticles()
    {
        if ($this->type === 'twitter') {
            $this->updateTwitterArticles();
        } elseif ($this->type === 'facebook') {
            $this->updateFacebookArticles();
        } elseif ($this->type === 'rss') {
            $this->updateRssArticles();
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
        if ($this->type === 'twitter') {
            $this->updateTwitterSources();
        } elseif ($this->type === 'facebook') {
            $this->updateFacebookSources();
        } elseif ($this->type === 'rss') {
            $this->updateRssSources();
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
        echo 'Updating last run time -> '. $this->type . "\n";
        $DB->updateLastCronTime($this->type);
    }
}
