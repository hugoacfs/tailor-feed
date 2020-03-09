<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 * Twitter class.
 * Extends Source.
 */
class Twitter extends Source
{
    /**
     * Url refers to the url of the Source,
     * which for Twitter it should always be
     * 'https://twitter.com/'.
     * @var string $url
     */
    protected $url = 'https://twitter.com/';
    /**
     * Type of Source object as in DB.
     * e.g. Twitter is 'twitter', as in the database.
     * @var string $type
     */
    protected $type = 'twitter';
    /**
     * The string value which holds the settings used for Twitter API queries.
     * Change this string according to the instructions found here: https://developer.twitter.com/en/docs/tweets/timelines/api-reference/get-statuses-user_timeline
     * @var string $requestExtraSettings
     */
    private $requestExtraSettings = '&include_rts=false&tweet_mode=extended&since_id=';
    /**
     * An object containing the Tweets fetched from the Twitter API query.
     * @var object $tweets
     */
    protected $tweets;
    /**
     * Builds an array of Articles belonging to the Twitter object.
     * Populates $this->articles with the relevant Article objects, using TwitterAPIExchange.
     * @return void
     */
    public function buildArticles(): void
    {
        $lastArticleUniqueId = Article::getLatestArticleUId($this->dbId);
        if ($lastArticleUniqueId === 0) $lastArticleUniqueId = 1132305067937714178; //default value cannot be 0 for twitter API
        $getfield = '?screen_name=' . $this->reference . $this->requestExtraSettings . $lastArticleUniqueId;
        $apiUrl = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $this->tweets = $this->twitterQuery($apiUrl, $getfield);
        $this->articles = array();
        if (!$this->tweets || count($this->tweets) === 0) return;
        $this->name = $this->tweets[0]->user->name;
        foreach ($this->tweets as $tweet) {
            $isQuote = ($tweet->is_quote_status === "true");
            $isReply = ($tweet->in_reply_to_status_id > 0);
            if ($isQuote || $isReply) continue; //if not original content, ignore
            $creationDate = $tweet->created_at;
            $timestamp = strtotime($creationDate);
            if ($timestamp < weeksAgo(8)) continue; //if older than 8 weeks, ignore
            $idStr = $tweet->id_str;
            $message = $tweet->full_text;
            $mediaLinksObj = $tweet->extended_entities->media ?? [];
            $media = $this->createMediaArray($mediaLinksObj) ?? [];
            $topics = extractHashtags($message) ?? [];
            $builder = array(
                'uniqueId' => $idStr,
                'ownerId' => $this->dbId,
                'creationDate' => $timestamp,
                'body' => $message,
                'topics' => $topics,
                'media' => $media
            );
            $this->articles[] = new Article($builder);
        }
    }
    /**
     * Creates an array required for media parsing into Article object for publishing.
     * @param object From Twitter API's JSON request.
     * @return array An assoc array containing URLs and media type.
     */
    function createMediaArray($mediaObject): array
    {
        $arr = [];
        foreach ($mediaObject as $media) {
            switch ($media->type) {
                case 'video':
                    $url = $media->video_info->variants[0]->url;
                    break;
                case 'photo':
                    $url = $media->media_url_https;
                    break;
                default:
                    continue;
            }
            $arr[] = array('url' => $url, 'type' => $media->type);
        }
        return $arr;
    }
    /**
     * It returns an array containing all sources as a Twitter object from the DB.
     * @return array of all the twitter sources that exist
     */
    public static function getAllSources($active = null): array
    {
        global $DB;
        $sources = array();
        /** Getting the account handles from db to request from twitter **/
        $type = 'twitter';
        $fetched = $DB->fetchAllSourcesByType($type, $active);
        /** By creating the Twitter objects*/
        foreach ($fetched as $row) {
            if ($row['type'] != $type) continue;
            $builder = null;
            $builder = array(
                'dbId' => $row['id'],
                'reference' => $row['reference'],
                'name' => $row['screenname'],
                'type' => 'twitter'
            );
            $sources[] = new Twitter($builder);
        }
        return $sources;
    }
    /**
     * Performs a query to Twitter API
     * Takes in the URL to the API and the required settings, returns false on failure, JSON on success.
     * @param string $apiUrl URL to the API to query Twitter from.
     * @param string $getfield string which contains the settings for the Twitter API query.
     * @return string $json_data
     * @return bool If it fails, returns false.
     */
    private static function twitterQuery(string $apiUrl, string $getfield)
    {
        global $CFG;
        // TODO CHECK EXIST BEFORE CONTINUE
        require_once($CFG->dirroot . '/vendor/TwitterAPIExchange/TwitterAPIExchange.php');
        $settings = $CFG->sources['twitter']['api_settings'];
        $requestMethod = 'GET';
        try {
            $twitter = new TwitterAPIExchange($settings);
            $json_data = $twitter->setGetfield($getfield)
                ->buildOauth($apiUrl, $requestMethod)
                ->performRequest();
            $httpsStatus = $twitter->getHttpStatusCode();
            echo "HTTP STATUS CODE: "  . $httpsStatus . "\n";
            if ($httpsStatus != 200) return false;
            return json_decode($json_data);
        } catch (Exception $ex) {
            handleException($ex);
        }
    }
    /**
     * Updates the DB details for all of the Twitter sources.
     * Fetches all twitter sources, then for each source it gathers Twitter data, then updates the DB's fields.
     * @return bool Returns true on success.
     */
    public static function updateSourcesDetails(): bool
    {
        global $DB;
        $type = 'twitter';
        $fetched = $DB->fetchAllSourcesByType($type);
        $fieldImage = 'imagesource';
        $fieldName = 'screenname';
        foreach ($fetched as $row) {
            $twitterData = Twitter::getSourceData($row['reference']);
            $valueImage = $twitterData->profile_image_url_https;
            $valueImage = str_replace("normal.jpg", "400x400.jpg", $valueImage);
            $valueName = $twitterData->name;
            $id = $row['id'];
            $successName = $DB->updateSourcesFieldById($fieldName, $valueName, $id);
            $successImage = $DB->updateSourcesFieldById($fieldImage, $valueImage, $id);
            if (!$successName or !$successImage) return false;
        }
        return true;
    }
    /**
     * Fetches the data for a Twitter account @$reference given.
     * Makes the Twitter API request required to get public account data.
     * @param string $reference
     * @return object $twitterData
     */
    protected static function getSourceData(string $reference): object
    {
        $apiUrl = 'https://api.twitter.com/1.1/users/show.json';
        $getfield = '&screen_name=' . $reference;
        $twitterData = Twitter::twitterQuery($apiUrl, $getfield);
        if (!$twitterData) return new stdClass;
        return $twitterData;
    }
}
