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
     * 
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
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
    /**
     * Publishes the Article objects given to the DB.
     * Won't run if array is empty. Makes sure Article is not yet on DB before insertion.
     * @param array $articlesToPublish contains Article objects.
     * @return bool Returns true on publish. False on failure to publish or nothing to publish.
     */
    public static function publishArticles(array $articlesToPublish): bool
    {
        global $DB;
        if (empty($articlesToPublish)) {
            return false;
        }
        foreach ($articlesToPublish as $article) {
            $creationDate = $article->creationDate;
            $ownerId = $article->ownerId;
            $message = $article->body;
            $uniqueId = $article->uniqueId;
            $fetched = $DB->fetchArticleByUniqueId($uniqueId);
            $uniqueIdNotExists = (count($fetched) === 0);
            if ($uniqueIdNotExists) {
                $insertSuccess = $DB->insertNewArticleEntry($ownerId, $uniqueId, $creationDate, $message);
                if ($insertSuccess) {
                    $lastInsertId = $DB->PDOgetlastinsertid();
                    $article->dbId = intval($lastInsertId);
                    Article::linkTopics($article);
                    Article::linkMedia($article);
                }
            }
        }
        return true;
    }
    /**
     * Finds the latest Article in DB by Source ID and Type.
     * For each of the Sources, it finds the latest Article published.
     * @param string $type Type of Article e.g. Twitter.
     * @param int $id DB id of Source of Article.
     * @return int Unique identifier or -1 if no Article is published yet.
     */
    public static function getLatestArticleUId(string $type, int $id): int
    {
        global $DB;
        if ($type === 'twitter' && !empty($id)) {
            $fetch = $DB->fetchLatestTwitterArticle($id);
            $countFetch = $fetch->rowCount();
            if ($countFetch != 1) {
                return -1;
            }
            $fetched = $fetch->fetch();
            return intval($fetched['MAX(a.uniqueidentifier)']);
        }
    }
    /**
     * Links an article to certain topics that exist in the DB
     * @param Article $article The object to link topics to
     */
    static public function linkTopics($article)
    {
        global $DB;
        if (empty($article->topics)) {
            return false;
        }
        $fetchedtopics = $DB->fetchAllTopics();
        $topicsList = array();
        foreach ($fetchedtopics as $row) {
            $topicsList[] = $row['name'];
        }
        $validTopics = array();
        $validTopics = array_intersect($topicsList, $article->topics);
        $validIds = array();
        foreach ($validTopics as $topic) {
            $fetchedid = $DB->fetchTopicId($topic);
            foreach ($fetchedid as $row) {
                $validIds[] = $row['id'];
            }
        }
        $DB->insertArticlesTopics($validIds, $article->dbId);
    }
    /**
     * 
     * @param Article $article The object to link topics to
     */
    static public function linkMedia($article)
    {
        global $DB;
        if (empty($article->media)) {
            return false;
        }
        $DB->insertMediaLinks($article->media, $article->dbId);
    }
    /**
     * It returns an array containing all topics as Topic objects stdClass from the DB.
     * @return array $sources
     */
    public static function getAllTopics($active = true): array
    {
        global $DB;
        $topics = array();
        if ($active) {
            $result = $DB->fetchAllActiveTopics();
        } else {
            $result = $DB->fetchAllTopics();
        }
        foreach ($result as $row) {
            $name = $row['name'];
            $id = $row['id'];
            $description = $row['description'];
            $thisTopic = new stdClass();
            $thisTopic->dbId = $id;
            $thisTopic->name = $name;
            $thisTopic->description = $description;
            $topics[] = $thisTopic;
        }
        return $topics;
    }
    /**
     * It returns the number of sources in the DB as an integer.
     * @return int
     */
    public static function getAllTopicsIds(): array
    {
        global $DB;
        $topicsIds = array();
        $fetched = $DB->fetchAllTopics();
        foreach ($fetched as $row) {
            $topicsIds[] = $row['id'];
        }
        return $topicsIds;
    }
}
