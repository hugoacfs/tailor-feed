<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
// ARTICLE CLASS
class Article
{
    /**
     * Body is the main message of the article.
     * @var string
     */
    public $dbId;
    /**
     * Body is the main message of the article.
     * @var string
     */
    public $body;
    /**
     * Owner ID corresponds to the database unique key for the owner of this article.
     * e.g. @chiuni Twitter would be 1
     * @var int
     */
    public $ownerId;
    /**
     * the type of article source it comes from
     * @var string
     */
    public $type;
    /**
     * This is used when displaying the article.
     * Optinal.
     * @var string
     */
    public $ownerName;
    /**
     * This is used when displaying the article.
     * Optinal.
     * @var string
     */
    public $ownerReference;
    /**
     * This is used when displaying the article.
     * Optinal.
     * @var string
     */
    public $imageSource;
    /**
     * Source URL
     * @var string
     */
    public $url;
    /**
     * Creation Date is the UNIX time in seconds when this article was first created.
     * e.g. 1580199862
     * @var int
     */
    public $creationDate;
    /**
     * This unique ID identifies the article, it should be unique for all articles, regardless of type or source.
     * e.g. 'asdg235ywregsdt'
     * @var string
     */
    public $uniqueId;
    /**
     * Topics is the array of strings (no spaces) which this article corresponds to. Topics should include a hashtag.
     * e.g. '#universityofchichester'
     * @var array
     */
    public $topics;
    /**
     * Array of media objects which has attributes ["url" : "https..", "type": "video|photo"]
     * @var array 
     */
    public $media;
    /**
     * Constructor for Articles.
     * Dynamic constructor, it will build the Article object depending on 
     * what the array parameter contains.
     * Array must be like:
     * @param array $builder 
     * Where $builder = ["key" => "value"]
     * And "key" is string property of Source class
     * and "value" is a string to be passed to that property.
     * 
     */
    function __construct(array $builder)
    {
        foreach ($builder as $key => $value) {
            if (property_exists($this, $key)) $this->$key = $value;
        }
    }
    /**
     * Inserts a new article entry on the DB as per article objects generated from external sources.
     * @param array $articlesToPublish Array containing Article objects to publish.
     * @return bool Returns true on publish or nothing to publish. False on failure to publish.
     * @throws PDOException On PDO issues when inserting new Articles or media/topics links.
     */
    public static function publishArticles(array $articlesToPublish): bool
    {
        global $DB;
        if (empty($articlesToPublish)) return true;
        $success = true;
        foreach ($articlesToPublish as $article) {
            echo ".";
            $fetched = $DB->fetchArticleByUniqueId($article->uniqueId);
            $uniqueIdExists = !(count($fetched) === 0);
            if ($uniqueIdExists) continue; //preventing duplicates being inserted
            $insertSuccess = $DB->insertNewArticleEntry(
                $article->ownerId,
                $article->uniqueId,
                $article->creationDate,
                $article->body
            );
            if (!$insertSuccess) {
                $success = false;
                error_log("Failed to insert article for Source ID: {$article->ownerId}\n");
                continue;
            }
            $lastInsertId = $DB->PDOgetlastinsertid();
            $article->dbId = intval($lastInsertId);
            Article::linkTopics($article->topics, $article->dbId);
            Article::linkMedia($article->media, $article->dbId);
        }
        echo "\n";
        return $success;
    }
    /**
     * Finds the latest Article in DB by Source ID and Type.
     * For each a Source, it finds the latest Article published.
     * @param string $type Type of Article e.g. Twitter.
     * @param int $id DB id of Source of Article.
     * @return int Unique identifier or -1 if no Article is published yet.
     */
    public static function getLatestArticleUId(int $id): int
    {
        global $DB;
        if (!$id) return 0;
        $fetched = $DB->fetchLatestTwitterArticle($id); //TODO: will this work with facebook and rss?
        $fetched = $fetched[0]; //gets the first row
        if (!$fetched) return 0;
        return intval($fetched['MAX(a.uniqueidentifier)']);
    }
    /**
     * Links an article to certain topics that exist in the DB
     * @param array $topics An array of topics found in the body of an Article.
     * @param int $id The id of the Article as found in the DB.
     * @return bool True on all links successfully added, False on failure to add all links. False on array is empty.
     */
    public static function linkTopics(array $topics, int $id): bool
    {
        global $DB;
        if (empty($topics)) return false;
        $fetchedtopics = $DB->fetchAllTopics();
        $topicsList = [];
        foreach ($fetchedtopics as $row) {
            $topicsList[$row['id']] = $row['name'];
        }
        $validTopics = array_intersect($topicsList, $topics);
        $validIds = array_keys($validTopics);
        return $DB->insertArticlesTopics($validIds, $id);
    }
    /**
     * Links an article to media urls.
     * @param array $media An array of media, containing urls and type of media, found in Article.
     * @param int $id The id of the Article as found in the DB.
     * @return bool True on all links successfully added, False on failure to add all links. False on array is empty.
     */
    public static function linkMedia(array $media, int $id): bool
    {
        global $DB;
        if (empty($media)) return false;
        return $DB->insertMediaLinks($media, $id);
    }
    /**
     * It returns an array containing all topics as Topic objects stdClass from the DB.
     * @return array $sources
     */
    public static function getAllTopics(): array
    {
        global $DB;
        $topics = [];
        $result = $DB->fetchAllTopics(true);
        foreach ($result as $row) {
            $thisTopic = new stdClass();
            $thisTopic->dbId = $row['id'];
            $thisTopic->name = $row['name'];
            $thisTopic->description = $row['description'];
            $topics[] = $thisTopic;
        }
        return $topics;
    }
    /**
     * Returns an array of topic ids.
     * @return array An array containing all topic ids from the DB.
     */
    public static function getAllTopicsIds(): array
    {
        global $DB;
        $topicsIds = [];
        $fetched = $DB->fetchAllTopics();
        foreach ($fetched as $row) $topicsIds[] = $row['id'];
        return $topicsIds;
    }
}
