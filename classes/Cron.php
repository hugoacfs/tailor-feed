<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 * Cron helper class, plus misc. cron tasks that don't belong anywhere else.
 */
class Cron {

    /**
     * Prints out a header for each cron task.
     * @param string $component The task which is running
     * @param int $starttime Timestamp
     */
    public static function cronHeader(string $component, int $starttime) {
        echo "Initiating Cron Job for " . ucwords($component) . " @ ". date('r', $starttime) . "\n";
    }

    /**
     * Prints out a footer for each cron task.
     * @param string $component The task which is running
     * @param int $starttime Timestamp
     * @param int $endtime Timestamp
     */
    public static function cronFooter(string $component,int $starttime,int $endtime) {
        $jobDuration = $endtime - $starttime;
        echo " Duration = " . $jobDuration . " seconds.\n";
        echo "Ending job for " . ucwords($component) . " @ ". date('r', $endtime) ."\n";
    }

    /**
     * Should Cron run this task now.
     * @param int $lastrun Time of last run.
     * @param int $interval Time required between each run.
     * @return bool runNow?
     */
    public static function runNow($lastrun, $interval) : bool {
        $now = time();
        $nextruntime = $lastrun + $interval;
        return ($nextruntime < $now);
    }

    /**
     * Creates an error message to be handled by cron.
     * @param string $component What part of the system is generating the message.
     * @param string $message The message
     * @return string Joined up component and message.
     */
    public static function error($component, $message) : string {
        return $component . ': ' . $message;
    }

    /**
     * Delete articles older than the time specified in the config.
     */
    public static function pruneArticles() {
        global $CFG, $DB;
        if ($CFG->articles_recycle_mode !== 'on') {
            echo "Article pruning disabled.\n";
            return;
        }

        $lastrun = intval($CFG->articles_recycle_last_cron); //last time run
        $interval = intval($CFG->articles_recycle_cron); //cron interval
        if (!Cron::runNow($lastrun, $interval)) {
            echo "Not time to prune Articles.\n";
            return;
        }
        $start = time();
        self::cronHeader('Articles', $start);
        echo " Deleting old articles\n";
        $since = time() - $CFG->articles_recycle_interval;
        if($DB->deleteArticlesOlderThan($since)) {
            $DB->updateRecycleCronTime('articles');
        }
        self::cronFooter('Articles', $start, time());
    }

    /**
     * Delete users after a time of inactivity.
     */
    public static function pruneUsers() {
        global $CFG, $DB;
        if ($CFG->users_recycle_mode !== 'on') {
            echo "User pruning not enabled.\n";
            return;
        }

        $lastrun = intval($CFG->users_recycle_last_cron); //last time run
        $interval = intval($CFG->users_recycle_cron); //cron interval
        if (!Cron::runNow($lastrun, $interval)) {
            echo "Not time to prune Users.\n";
            return;
        }

        $start = time();
        self::cronHeader('Users', $start);
        echo " Deleting old users\n";
        $since = time() - $CFG->users_recycle_interval;
        if($DB->deleteUsersOlderThan($since)) {
            $DB->updateRecycleCronTime('users');
        }
        self::cronFooter('Users', $start, time());
    }

}