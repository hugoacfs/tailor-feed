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
    public function __construct(string $userName)
    {
        global $DB;
        $fetchUser = $DB->fetchUserByUsername($userName);
        $fetchedUser = $fetchUser[0];
        $this->dbId = intval($fetchedUser['id']);
        $this->userName = $userName;
        $this->givenName = $fetchedUser['givenname'];
        $this->subscribedList = array();
        $this->topicsList = array();
        $this->updateUserSubcribedList();
        $this->updateUserTopicsList();
    }
    /**
     * Creates the user's subscribed list.
     * Creates $this->subscribedList
     * @return void
     */
    public function updateUserSubcribedList(): void
    {
        $this->subscribedList = null;
        global $DB;
        $fetched = $DB->fetchUserPreferencesByUserId($this->dbId);
        foreach ($fetched as $row) {
            $type = $row['type'];
            $reference = $row['reference'];
            $id = $row['id'];
            $name = $row['screenname'];
            $builder = null;
            $builder = array(
                'dbId' => $id,
                'reference' => $reference,
                'type' => $type,
                'name' => $name
            );
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
        $this->topicsList = null;
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
    public function getPreferences(string $type)
    {
        switch ($type) {
            case 'source':
                return $this->subscribedList;
            case 'topic':
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
     * Makes changes to the DB user's preferences accordin to the new preferences list.
     * @param array $preferencesList a list of preferences to update as requested by HTML form.
     * @param string $type of preference, could be 'source' or 'topic'
     * @return void
     */
    public function updatePreferences(array $preferencesList, string $type): void
    {
        $toAddList = array();
        $toRemoveList = array();
        $currentList = array();
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
     * Returns an array of Articles.
     * According to user preferences, it fetches the relevant articles to show the user.
     * @param int page to return by, default is 1 meaning not offset
     * @return array $articlesList array of Article objects which the user is subscribed to.
     */
    private function buildSubscribedArticles(int $page = 1): array
    {
        global $DB;
        $articlesList = [];
        $timeInterval = weeksAgo(8);
        $subscribedList = $this->subscribedList ?? [];
        $topicsList = $this->topicsList ?? [];
        $fetched = $DB->fetchUserSubscribedArticles($subscribedList, $topicsList, $timeInterval, $page);
        if ($fetched == null) {
            return $articlesList;
        }
        foreach ($fetched as $row) {
            $fetchMedia = $DB->fetchMediaUrlsPerArticleId($row['id']);
            $media = [];
            foreach ($fetchMedia as $m) {
                $media[] = array(
                    'url' => $m['url'],
                    'type' => $m['type']
                );
            }
            $builder = array(
                'dbId' => $row['id'],
                'uniqueId' => $row['uniqueidentifier'],
                'ownerReference' => $row['reference'],
                'ownerName' => $row['screenname'],
                'ownerId' => $row['sourceid'],
                'creationDate' => $row['creationdate'],
                'body' => $row['body'],
                'url' => '',
                'type' => $row['type'],
                'imageSource' => $row['imagesource'],
                'topics' => extractHashtags($row['body']),
                'media' => $media
            );
            $articlesList[] = new Article($builder);
        }
        return $articlesList;
    }
    /**
     * Turns Array of objects into JSON
     * @param int page to return by, default is 1 meaning not offset
     * @return string JSON data.
     */
    public function getArticlesJSON(int $page = 1): string
    {
        return json_encode($this->buildSubscribedArticles($page));
    }
    /**
     * HTML builder method for displaying the articles.
     * @param array $builder 
     * Where $builder = ["key" => $var]
     * And "key" is string is a variable name
     * and "value" is the corresponding variable.
     */
    private function buildTimelineHtml(array $builder): string
    {
        /**
         * TIMELINE HTML is the html of the feed to be returned
         * Change this to meet the requirements of the client where this
         * will be displayed
         */
        if (!$builder) {
            $message = "Nothing to show 😮. Looks like you've reached the end of the page.";
            return '
            <div id="end-news" class="card-body ">
                    <h4 class="card-title">
                        <a class=" card-link">
                            Uh oh!
                        </a>
                    </h4>
                    <p class="card-text">' . $message . '</p>
                    <span style="display: none;">newscode:340</span>
            </div>
            <hr class="thin-hr">';
        }
        return '
        <div class="card-body ">
            <a href="' . $builder['accountUrl'] . '" target="uni_news" class=" card-link">
                <div class="timeline-badge mt-1 ml-1">
                    <div class="spinner-border text-primary">
                    </div>
                    <image alt="' . $builder['name'] . ' profile image." class="timeline-img"
                        src="' . $builder['profile_image'] . '" width="50px">
                </div>
            </a>
            <h5 class="card-subtitle mb-2 text-muted">
                <a title="' . $builder['name'] . '" href="' . $builder['accountUrl'] . '" target="uni_news" class=" card-link">
                    @' . $builder['screen_name'] . '
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
            <p class="card-text">' . $builder['message'] . $builder['media'] . ' </p>
        </div>
        <hr class="thin-hr">';
    }
    /**
     * Returns html for the articles to be displayed
     * @param int page to return by, default is 1 meaning not offset
     * @return string HTML of articles subscribed by user
     */
    public function displaySubscribedArticles(int $page = 1): string
    {
        $articlesToDisplay = $this->buildSubscribedArticles($page);
        $htmlHolder = '';
        if (!$articlesToDisplay) {
            return $this->buildTimelineHtml([]); //builds empty HTML
        }
        foreach ($articlesToDisplay as $article) {
            $message = convertHashtags(convertMentions(convertLinks($article->body)));
            $timestamp = $article->creationDate;
            $name = $article->ownerName;
            $screen_name = $article->ownerReference;
            $profile_image = $article->imageSource;
            switch($article->type){
                case 'twitter':
                    $originalUrl = 'https://twitter.com/' . $screen_name . '/status/' . $article->uniqueId;
                    $accountUrl = 'https://twitter.com/' . $screen_name;
                    break;
            }
            $media = $article->media ?? array();
            $mediaHTML = '';
            $firstStatus = 'active';
            $carouselHtml = '';
            foreach ($media as $m) {
                switch ($m['type']) {
                    case 'photo':
                        $mediaHTML .= '
                        <div class="carousel-item ' . $firstStatus . '">
                            <img class="img-fluid mx-auto d-block rounded " src="' . $m['url'] . '?name=medium" alt="Article Image">
                        </div>';
                        break;
                    case 'video':
                        $mediaHTML .= '
                        <div class="carousel-item ' . $firstStatus . '">
                            <video class="img-fluid mx-auto d-block rounded " src="' . $m['url'] . '?name=small" controls="" alt="Article Video">
                        </div>';
                        break;
                }
                $firstStatus = '';
            }
            if ($article->media) {
                $carouselHtml = '
                <div id="carouselArticle' . $article->dbId . '" class="carousel slide" data-ride="carousel" data-interval="false">
                    <div class="carousel-inner">
                        ' . $mediaHTML . '
                    </div>
                    <a class="carousel-control-prev" href="#carouselArticle' . $article->dbId . '" role="button" data-slide="prev">
                        <span class="fas fa-arrow-left fa-lg text-dark" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next " href="#carouselArticle' . $article->dbId . '" role="button" data-slide="next">
                        <span class="fas fa-arrow-right fa-lg text-dark" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>';
            }
            $builder = null;
            $builder = array(
                'type' => ucfirst($article->type),
                'profile_image' => $profile_image,
                'accountUrl' => $accountUrl,
                'name' => $name,
                'message' => $message,
                'screen_name' => strtolower($screen_name),
                'timestamp' => $timestamp,
                'originalUrl' => $originalUrl,
                'media' => $carouselHtml
            );
            $htmlHolder .= $this->buildTimelineHtml($builder);
        }
        return $htmlHolder;
    }
}
