<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
class User
{
    /**
     * The ID as found in the DB.
     * @var int
     */
    public $dbId;
    /**
     * The username as found in the DB.
     * @var string
     */
    private $userName;
    /**
     * The user's first name as found in the DB.
     * @var string
     */
    private $givenName;
    /**
     * A list of subscribed sources according to the user's preferences in the DB.
     * @var array
     */
    private $subscribedList;
    /**
     * A list of subscribed sources according to the user's preferences in the DB.
     * @var array
     */
    private $topicsList;
    /**
     * Constructor class gets the user's details from the DB by username.
     * The subscribed list is built.
     * @param string $userName is the username as found in the DB.
     */
    public function __construct(string $userName = '')
    {
        global $DB;
        if ($userName != '') {
            $fetchUser = $DB->fetchUserByUsername($userName);
            $fetchedUser = $fetchUser[0];
            $this->dbId = intval($fetchedUser['id']);
            $this->userName = $userName;
            $this->givenName = $fetchedUser['givenname'];
            $this->subscribedList = $this->topicsList = [];
            $this->updateUserSubcribedList();
            $this->updateUserTopicsList();
        }
    }
    /**
     * Creates the user's subscribed list.
     * Creates $this->subscribedList
     * @return void
     */
    public function updateUserSubcribedList(): void
    {
        $this->subscribedList = [];
        global $DB;
        $fetched = $DB->fetchUserPreferencesByUserId($this->dbId);
        foreach ($fetched as $row) {
            $type = $row['type'];
            $reference = $row['reference'];
            $id = $row['id'];
            $name = $row['screenname'];
            $builder = null;
            $builder = [
                'dbId' => $id,
                'reference' => $reference,
                'type' => $type,
                'name' => $name
            ];
            $this->subscribedList[] = new Source($builder);
        }
        $fetched = null;
    }
    /**
     * Creates the user's topics list.
     * Creates $this->topicsList
     * @return void
     */
    public function updateUserTopicsList(): void
    {
        $this->topicsList = [];
        global $DB;
        $fetched = $DB->fetchUserTopicsByUserId($this->dbId);
        foreach ($fetched as $row) {
            $name = $row['name'];
            $id = $row['id'];
            $thisTopic = new stdClass();
            $thisTopic->dbId = $id;
            $thisTopic->name = $name;
            $this->topicsList[] = $thisTopic;
        }
        $fetched = null;
    }
    /**
     * Returns the user preferences by type
     * @param string $type of preference, could be 'source' or 'topic'
     * @return array of preferences
     */
    public function getPreferences(string $type): array
    {
        switch ($type) {
            case 'sources':
                return $this->subscribedList;
            case 'topics':
                return $this->topicsList;
            default:
                return [];
        }
    }
    /**
     * Checks if a specific source is subscribed by the user.
     * @param string $value is the Source to be checked.
     * @param string $type of preference, could be 'source' or 'topic'
     * @return bool True if source is subscribed.
     */
    private function isPreferenceSubscribed(int $preferenceId, string $type): bool
    {
        global $DB;
        $fetched = $DB->userPreferencesCrudQuery($type, $preferenceId, $this->dbId, 'select');
        if ($fetched) return true;
        return false;
    }
    /**
     * Inserts a source as subscribed to DB by sourceID.
     * @param int $sourceId is the Source ID in the DB.
     * @param string $type of preference, could be 'source' or 'topic'
     * @return bool returns True on success or if source already subscribed.
     */
    private function addPreference(int $preferenceId, string $type): bool
    {
        global $DB;
        $isPreferenceSubscribed = $this->isPreferenceSubscribed($preferenceId, $type);
        if ($isPreferenceSubscribed) return $isPreferenceSubscribed;
        return $DB->userPreferencesCrudQuery($type, $preferenceId, $this->dbId, 'insert');
    }
    /**
     * Removes a source as subscribed from DB by sourceID.
     * @param int $sourceId is the Source ID in the DB.
     * @param string $type of preference, could be 'source' or 'topic'
     * @return bool returns True on success or if source already unsubscribed.
     */
    private function removePreference(int $preferenceId, string $type): bool
    {
        global $DB;
        $isPreferenceSubscribed = $this->isPreferenceSubscribed($preferenceId, $type);
        if ($isPreferenceSubscribed) {
            return $DB->userPreferencesCrudQuery($type, $preferenceId, $this->dbId, 'delete');
        }
        return !$isPreferenceSubscribed;
    }
    /**
     * Makes changes to the DB user's preferences according to the new preferences list.
     * @param array $preferencesList a list of preferences to update as requested by HTML form.
     * @param string $type of preference, could be 'source' or 'topic'
     * @return void
     */
    public function updatePreferences(array $preferencesList, string $type): void //TODO: perhaps improve this
    {
        $toAddList = $toRemoveList = $currentList = [];
        switch ($type) {
            case 'source':
                $operatingList = $this->subscribedList;
                break;
            case 'topic':
                $operatingList = $this->topicsList;
                break;
            default:
                return;
        }
        if ($operatingList) {
            foreach ($operatingList as $object) {
                if (is_a($object, 'Source')) $currentList[] = $object->getDbId();
                else $currentList[] = $object->dbId;
            }
        }
        foreach ($preferencesList as $preference) {
            $isInArray = in_array($preference, $currentList, true);
            if (!($isInArray)) $toAddList[] = $preference;
        }
        foreach ($currentList as $preference) {
            $isInArray = in_array($preference, $preferencesList, true);
            if (!($isInArray)) $toRemoveList[] = $preference;
        }
        foreach ($toRemoveList as $removeId) {
            $this->removePreference($removeId, $type);
        }
        foreach ($toAddList as $addId) {
            $this->addPreference($addId, $type);
        }
        $this->updateUserSubcribedList();
        $this->updateUserTopicsList();
    }
    /**
     * Turns Array of objects into JSON
     * @param int page to return by, default is 1 meaning not offset
     * @return string JSON data.
     */
    public function getArticlesJSON(int $page = 1)
    {
        return json_encode($this->constructArticles($page), JSON_HEX_QUOT | JSON_HEX_TAG); // There was an issue with "message": in the json object, anchor tags problem https://stackoverflow.com/questions/9764598/json-encode-not-working-with-a-html-string-as-value 
    }
    /**
     * HTML builder method for displaying the articles.
     * @param array $builder 
     * Where $builder = ["key" => $var]
     * And "key" is string is a variable name
     * and "value" is the corresponding variable.
     * @return string HTML - Returns html for a card which has all the articles ready for display on a bootstrap4 site.
     */
    private function buildTimelineHtml(array $builder): string
    {
        /**
         * TIMELINE HTML is the html of the feed to be returned
         * Change this to meet the requirements of the client where this
         * will be displayed
         */
        if (isset($builder['lastArticle'])) {
            if ($this->userName == 'default') return '';
            $sourcesButton =  '<button type="button" class="pages-btn btn btn-dark btn-outline-light mr-1 ml-1 border" data-toggle="modal" data-content="pages" data-target="#pagesModal">
                            <span class="fas fa-at menu-fa" aria-hidden="true"></span> 
                            <span class="preferences-btn-text">Following</span>
                        </button>';
            $topicsButton =  '<button type="button" class="topics-btn btn btn-dark btn-outline-light mr-1 ml-1 border" data-toggle="modal" data-content="topics" data-target="#topicsModal">
                            <span class="fas fa-hashtag menu-fa" aria-hidden="true"></span> 
                            <span class="preferences-btn-text">Topics</span>
                        </button>';
            $message = "For more news, follow more accounts here " . $sourcesButton . " <br> Or try following some topics here " . $topicsButton;
            $endMessage = '
            <div id="end-news" class="card-body ">
                    <h4 class="card-title">
                        <a class=" card-link">
                            You are all caught up!
                        </a>
                    </h4>
                    <p class="card-text">' . $message . '</p>
                    <span style="display: none;">newscode:340</span>
            </div>
            <hr class="thin-hr">'; //newscode:340 means stop refreshing ajax VERY BAD need changing
            return $endMessage;
        }
        if (!$builder) {
            $button =  '<button type="button" class="pages-btn btn btn-dark btn-outline-light mr-1 ml-1 border" data-toggle="modal" data-content="pages" data-target="#pagesModal">
                            <span class="fas fa-at menu-fa" aria-hidden="true"></span> 
                            <span class="preferences-btn-text">Following</span>
                        </button>';
            $message = "Uh oh, there is no new activity on your feed 😮. <br> Try following an account here " . $button;
            $endMessage = '
            <div id="end-news" class="card-body ">
                    <h4 class="card-title">
                        <a class=" card-link">
                            No new activity...
                        </a>
                    </h4>
                    <p class="card-text">' . $message . '</p>
                    <span style="display: none;">newscode:340</span>
            </div>
            <hr class="thin-hr">'; //newscode:340 means stop refreshing ajax
            return $endMessage;
        }
        $media = $builder['media'] ?? [];
        $mediaHTML = '';
        $firstStatus = 'active';
        $carouselHtml = '';
        $carouselHtmlNav = '';
        $numOfMediaItems = count($builder['media']);
        foreach ($media as $m) {
            switch ($m['type']) {
                case 'photo':
                    $mediaHTML .= '
                        <div class="my-carousel carousel-item ' . $firstStatus . '">
                            <img class="img-fluid mx-auto d-block rounded " src="' . $m['url'] . '?name=medium" alt="Article Image">
                        </div>';
                    break;
                case 'video':
                    $mediaHTML .= '
                        <div class="my-carousel carousel-item ' . $firstStatus . '">
                            <video class="img-fluid mx-auto d-block rounded " controls muted loop alt="Article Video">
                                <source src="' . $m['url'] . '?name=small" />
                            </video>
                        </div>';
                    break;
            }
            $firstStatus = '';
        }
        if ($numOfMediaItems > 1) {
            $carouselHtmlNav = '<a class="carousel-control-prev" href=".carouselArticle' . $builder['articleId'] . '" role="button" data-slide="prev">
                                    <span class="fas fa-arrow-left fa-lg text-dark" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next " href=".carouselArticle' . $builder['articleId'] . '" role="button" data-slide="next">
                                    <span class="fas fa-arrow-right fa-lg text-dark" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>';
        }
        if ($builder['media']) {
            $carouselHtml = '
            <div class="carousel-pop">
                <div id="carouselArticle' . $builder['articleId'] . '" class="carouselArticle carouselArticle' . $builder['articleId'] . ' carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">
                        ' . $mediaHTML . '
                    </div>
                    ' . $carouselHtmlNav . '
                </div>
            </div>';
        }
        return '
        <div class="card-body ">
            <a href="' . $builder['accountUrl'] . '" target="uni_news" class=" card-link">
                <div class="timeline-badge mt-1 ml-1">
                    <img class="timeline-img"
                        src="' . $builder['profileImage'] . '" width="50">
                    <span class="img-spinner spinner-border text-primary"></span>
                </div>
            </a>
            <h5 class="card-subtitle mb-2 text-muted">
                <a title="' . $builder['name'] . '" href="' . $builder['accountUrl'] . '" target="uni_news" class=" card-link">
                    @' . $builder['referenceName'] . '
                    <i class="fab fa-twitter-square"></i>
                </a>
            </h5>
            <p>
                <a title="Link to source." href="' . $builder['originalUrl'] . '" target="uni_news">
                    <small class="text-muted">
                        <i class="glyphicon glyphicon-time"></i>
                        <i class="far fa-clock"> </i> ' . timeAgo($builder['timestamp']) . ' via ' . $builder['type'] . '
                    </small>
                    <i class="fas fa-link fa-xs"></i>
                </a>
            </p>
            <p class="card-text">' . $builder['message'] . '</p>
            <p><div class="text-center" >' . $carouselHtml . ' </div></p>
        </div>
        <hr class="thin-hr">';
    }

    /**
     * @deprecated
     * Returns an array of Article objects.
     * According to user preferences, it fetches the relevant articles to show the user.
     * @param int page to return by default is 1 meaning not offset
     * @return array $articlesList array of Article objects which the user is subscribed to.
     */
    private function buildSubscribedArticles(int $page = 1): array
    {
        global $DB;
        // $timeInterval = weeksAgo(8); //for now will allow users to go back as far as desired
        $timeInterval = '0';
        $meta = [];
        $meta['page'] = $page;
        $meta['since'] = $timeInterval;
        $fetchedArticles = $DB->fetchUserSubscribedArticles($this->subscribedList ?? [], $this->topicsList ?? [], $timeInterval, $page);
        if ($fetchedArticles == null) return [];
        $articlesList = [];
        foreach ($fetchedArticles as $article) {
            $fetchMedia = $DB->fetchMediaUrlsPerArticleId($article['id']);
            $media = [];
            foreach ($fetchMedia as $m) {
                $media[] = [
                    'url' => $m['url'],
                    'type' => $m['type']
                ];
            }
            $builder = [
                'dbId' => $article['id'],
                'uniqueId' => $article['uniqueidentifier'],
                'ownerReference' => $article['reference'],
                'ownerName' => $article['screenname'],
                'ownerId' => $article['sourceid'],
                'creationDate' => $article['creationdate'],
                'body' => $article['body'],
                'url' => '',
                'type' => $article['type'],
                'imageSource' => $article['imagesource'],
                'topics' => extractHashtags($article['body']),
                'media' => $media
            ];
            $articlesList[] = new Article($builder);
        }
        debug_to_console($articlesList);
        return $articlesList;
    }
    /**
     * @deprecated
     * Prepares an array with all article data requested.
     * @param int $page The page which the articles will be built, 1 being the first (most recent) articles.
     * @return array $builder - An associative array which holds the data for all the articles prepared.
     */
    public function prepareArticlesBuilder(int $page = 1): array
    {
        $builder = [];
        $articlesToDisplay = $this->constructArticles($page);
        $lastArticles = false;
        if (count($articlesToDisplay) < 10 && count($articlesToDisplay) > 0) $lastArticles = true;
        foreach ($articlesToDisplay as $article) {
            $message = convertHashtags(convertMentions(convertLinks($article->body)));
            $timestamp = $article->creationDate;
            $name = $article->ownerName;
            $referenceName = $article->ownerReference;
            $profileImage = $article->imageSource;
            switch ($article->type) {
                case 'twitter':
                    $originalUrl = 'https://twitter.com/' . $referenceName . '/status/' . $article->uniqueId;
                    $accountUrl = 'https://twitter.com/' . $referenceName;
                    break;
            }
            $builder[] = [
                'articleId' => $article->dbId,
                'type' => ucfirst($article->type),
                'profileImage' => $profileImage,
                'accountUrl' => $accountUrl,
                'name' => $name,
                'message' => $message,
                'referenceName' => strtolower($referenceName),
                'timestamp' => $timestamp,
                'timeago' => timeAgo($timestamp),
                'originalUrl' => $originalUrl,
                'media' => $article->media
            ];
        }
        if ($lastArticles) $builder['lastArticle'] = true;
        return $builder;
    }

    public function constructArticles(int $page = 1, int $since = 0)
    {
        global $DB;
        $fetchedArticles = $DB->fetchUserSubscribedArticles($this->subscribedList ?? [], $this->topicsList ?? [], $since, $page);
        $finalarticles = (count($fetchedArticles) < 10 && count($fetchedArticles) >= 0);
        $meta = [
            "page" => $page,
            "since" => $since,
            "finalarticles" => $finalarticles
        ];
        $prepArticles = [];
        foreach ($fetchedArticles as $rawArticle) {
            $fetchMedia = $DB->fetchMediaUrlsPerArticleId($rawArticle['id']);
            $rawMedia = [];
            foreach ($fetchMedia as $m) {
                $rawMedia[] = [
                    'url' => $m['url'],
                    $m['type'] => True
                ];
            }
            debug_to_console($rawMedia);
            $prepArticles[] = new Article(
                [
                    'dbId' => $rawArticle['id'],
                    'uniqueId' => $rawArticle['uniqueidentifier'],
                    'ownerReference' => $rawArticle['reference'],
                    'ownerName' => $rawArticle['screenname'],
                    'ownerId' => $rawArticle['sourceid'],
                    'creationDate' => $rawArticle['creationdate'],
                    'body' => $rawArticle['body'],
                    'url' => '',
                    'type' => $rawArticle['type'],
                    'imageSource' => $rawArticle['imagesource'],
                    'topics' => extractHashtags($rawArticle['body']),
                    'media' => $rawMedia
                ]
            );
        }
        $fineArticles = [];
        foreach ($prepArticles as $article) {
            $message = convertHashtags(convertMentions(convertLinks($article->body)));
            $timestamp = $article->creationDate;
            $name = $article->ownerName;
            $reference = $article->ownerReference;
            $profileImage = $article->imageSource;
            switch ($article->type) {
                case 'twitter':
                    $originalUrl = 'https://twitter.com/' . $reference . '/status/' . $article->uniqueId;
                    $accountUrl = 'https://twitter.com/' . $reference;
                    break;
            }
            $raw = new stdClass;
            foreach ($article as $key => $rawData) {
                $raw->$key = $rawData;
            }
            $mediaItems = [];
            foreach ($article->media as $key => $media) {
                $mediaItems[$key] = $media;

            }
            $fineArticles[] = [
                'articleId' => $article->dbId,
                'type' => ucfirst($article->type),
                'profileImage' => $profileImage,
                'accountUrl' => $accountUrl,
                'name' => $name,
                'message' => $message,
                'referenceName' => strtolower($reference),
                'timestamp' => $timestamp,
                'timeago' => timeAgo($timestamp),
                'originalUrl' => $originalUrl,
                'media' => $article->media,
                'raw' => $raw
            ];
        }
        $data = [
            "articles" => $fineArticles,
            "meta" => $meta
        ];
        debug_to_console($data);
        return $data;
    }


    /**
     * Returns html for the articles to be displayed
     * @param int page to return by, default is 1 meaning not offset
     * @return string HTML of articles subscribed by user
     */
    public function displaySubscribedArticles(int $page = 1): string
    {
        $htmlHolder = '';
        $data = $this->constructArticles($page);
        $blocks = $data;
        if (!$blocks) return $this->buildTimelineHtml([]);
        foreach ($blocks as $builder) $htmlHolder .= $this->buildTimelineHtml($builder);
        return $htmlHolder;
    }
}
