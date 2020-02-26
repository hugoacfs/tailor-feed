<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
class Connection
{
    /**
     * @var string $hostname for PDO connection 
     */
    private $hostname;
    /**
     * @var string $username for PDO connection
     */
    private $username;
    /**
     * @var string $password for PDO connection
     */
    private $password;
    /**
     * @var object $connection PDO object 
     */
    private $connection;
    function __construct(string $hostname, string $username, string $password)
    {
        $this->hostname = $hostname;
        $this->username = $username;
        $this->password = $password;
        try {
            $connection = new PDO($this->hostname, $this->username, $this->password) or die('Cannot connect to db');
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection = $connection;
        } catch (PDOException $e) {
            die();
            $this->connection = null;
        }
    }

    // INTERNAL QUERYING USING PDO
    /**
     * Internal query method
     * @param string $sql_query Query String for PDO::query()
     * @return PDOStatement Query Result
     */
    private function PDOquery(string $sql_query): PDOStatement
    {
        try {
            return ($this->connection)->query($sql_query);
        } catch (PDOException $e) {
            die();
            $this->connection = null;
        }
    }
    /**
     * Internal prepare method
     * @param string $sql_query Query String for PDO::prepare()
     * @return PDOStatement Query Result
     */
    private function PDOprepare(string $sql_query): PDOStatement
    {
        try {
            return ($this->connection)->prepare($sql_query);
        } catch (PDOException $e) {
            die();
            $this->connection = null;
        }
    }
    /**
     * Public lastInsertId method
     * @return int of the last inserted ID
     */
    public function PDOgetlastinsertid(): int
    {
        return intval(($this->connection)->lastInsertId());
    }

    // DB QUERIES for class Article:
    /**
     * Fetches Article matching the UniqueId
     * Article DB query
     * @param string $matchingUniqueId Unique Id of Article
     * @return PDOStatement Query Result
     */
    public function fetchArticleByUniqueId(string $matchingUniqueId): PDOStatement
    {
        $sql_string = "SELECT * FROM `articles` WHERE `uniqueidentifier` = '" . $matchingUniqueId . "' ";
        return $this->PDOquery($sql_string);
    }
    /**
     * Inserts Article into table
     * Article DB query
     * @param int $ownerId Id of Source
     * @param string $uniqueId Unique Id of Article
     * @param int $creationDate Creation date of Article
     * @param string $message Message body of Article
     * @return bool Returns query status, true for success.
     */
    public function insertNewArticleEntry(int $ownerId, string $uniqueId, int $creationDate, string $message): bool
    {
        $stmt = $this->PDOprepare(
            "INSERT INTO `articles` 
            (`sourceid`, `uniqueidentifier`, `creationdate`, `body`) 
            VALUES 
            (?, ?, ?, ?)"
        );
        return $stmt->execute([$ownerId, $uniqueId, $creationDate, $message]);
    }
    /**
     * Fetches the Article with max uniqueId 
     * Article DB query
     * @param int $id Unique Id of Article
     * @return PDOStatement Query Result
     */
    public function fetchLatestTwitterArticle(int $id): PDOStatement
    {
        $sql_select =
            "SELECT MAX(a.uniqueidentifier) 
            FROM `articles` AS `a`
            JOIN `sources` AS `s` ON s.id=a.sourceid
            WHERE s.type = 'twitter' 
            AND s.id = " . $id;
        return $this->PDOquery($sql_select);
    }
    /**
     * TODO: Complete Documenting
     */
    public function fetchAllTopics(): PDOStatement
    {
        $sql_string = "SELECT * FROM `topics` ";
        return $this->PDOquery($sql_string);
    }

    public function fetchTopicId(string $name): PDOStatement
    {
        $sql_string = "SELECT `id` FROM `topics` WHERE `name` = '" . $name . "' ";
        return $this->PDOquery($sql_string);
    }

    public function fetchMediaLinksArticle(int $id): PDOStatement
    {
        $sql_string = "SELECT * FROM `media_links` WHERE `id` = " . $id . " ";
        return $this->PDOquery($sql_string);
    }

    public function insertArticlesTopics(array $topics = array(), int $articleid)
    {
        if (empty($topics)) {
            return false;
        }
        foreach ($topics as $topicid) {
            $stmt = $this->PDOprepare(
                "INSERT INTO `articles_topics` 
                (`articleid`, `topicid`) 
                VALUES 
                (?, ?)"
            );
            $stmt->execute([$articleid, $topicid]);
        }
    }

    public function insertMediaLinks(array $media = array(), int $articleid)
    {
        if (empty($media)) {
            return false;
        }
        foreach ($media as $m) {
            $stmt = $this->PDOprepare(
                "INSERT INTO `media_links` 
                (`articleid`, `url`) 
                VALUES 
                (?, ?)"
            );
            $stmt->execute([$articleid, $m]);
        }
    }


    /** END Article QUERIES */
    /** DB QUERIES for class Source: */
    /**
     * Fetches All Sources from DB 
     * Source DB query
     * @return PDOStatement Query Result
     */
    public function fetchAllSources(): PDOStatement
    {
        $sql_string = "SELECT * FROM sources ";
        return $this->PDOquery($sql_string);
    }
    /**
     * Fetches All Sources from DB 
     * Source DB query
     * @return PDOStatement Query Result
     */
    public function fetchAllActiveSources(): PDOStatement
    {
        $sql_string = "SELECT * FROM sources WHERE `status` = 'active'";
        return $this->PDOquery($sql_string);
    }
    /**
     * Counts All Sources from DB 
     * Source DB query
     * @return int Query Result
     */
    public function countAllSources(): int
    {
        $result = $this->fetchAllSources();
        return $result->rowCount();
    }
    /**
     * Fetches Source Reference by Id 
     * Source DB query
     * @param int $sourceId the Id as in DB
     * @return PDOStatement Query Result
     */
    public function fetchSourceReferenceById(int $sourceId): PDOStatement
    {
        $id = intval($sourceId);
        $sql_string = "SELECT * FROM sources WHERE `id` = $id";
        return $this->PDOquery($sql_string);
    }
    /** END Source QUERIES */
    /**DB QUERIES FOR class Twitter */
    /**
     * Fetches Sources of specified Type 
     * Source DB query
     * @param string $type type of source
     * @return PDOStatement Query Result
     */
    public function fetchAllSourcesByType(string $type): PDOStatement
    {
        $sql_string = "SELECT * FROM sources WHERE type = '$type'";
        return $this->PDOquery($sql_string);
    }
    /**
     * Updates specified Source table field
     * Source DB query
     * @param string $field Table field as in DB
     * @param string $value New value 
     * @param int $id Id of Source
     * @return bool True on success
     */
    public function updateSourcesFieldById(string $field, string $value, int $id): bool
    {
        $stmt = $this->PDOprepare("UPDATE `sources` SET `$field` = :newvalue WHERE `id` = :id");
        $stmt->bindValue('newvalue', $value, PDO::PARAM_STR);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    /** END Twitter QUERIES */
    /**DB QUERIES FOR class User */
    /**
     * Fetches pages preferences by userId
     * User DB query
     * @param int $userid Id of Source
     * @return PDOStatement Query Result
     */
    public function fetchUserPreferencesByUserId(int $userId): PDOStatement
    {
        $sql_string = "SELECT * FROM `subscribed_sources` AS `ss` JOIN `sources` AS `s` ON `s`.`id` = `ss`.`sourceid` WHERE `userid` =" . $userId . " ORDER BY `s`.`reference`;";
        return $this->PDOquery($sql_string);
    }

    /**
     * Fetches topics preferences by userId
     * User DB query
     * @param int $userid Id of Source
     * @return PDOStatement Query Result
     */
    public function fetchUserTopicsByUserId(int $userId): PDOStatement
    {
        $sql_string = "SELECT * FROM `subscribed_topics` AS `st` JOIN `topics` AS `t` ON `t`.`id` = `st`.`topicid` WHERE `userid` =" . $userId . " ORDER BY `t`.`name`;";
        return $this->PDOquery($sql_string);
    }
    /**
     * User preferences CRD method
     * User DB query

     * @return PDOStatement Query Result for select query
     * @return bool for insert and delete returns true or false
     */
    public function userPreferencesCrudQuery(string $type, int $preferenceId, int $userId, string $action)
    {
        if ($action === 'select') {
            // $sql_string = "SELECT `id` FROM `subscribed_preferences` WHERE `name` = '$name' AND `value` = '" . $value . "' AND `userid` = $userId";
            $sql_string = "SELECT `id` FROM `subscribed_" . $type . "s` WHERE `" . $type . "id` = $preferenceId AND `userid` = $userId";
            return $this->PDOquery($sql_string);
        } elseif ($action === 'insert') {
            $stmt = $this->PDOprepare("INSERT INTO `subscribed_" . $type . "s` (`" . $type . "id`, `userid`) VALUES (?, ?);");
            return $stmt->execute([$preferenceId, $userId]);
        } elseif ($action === 'delete') {
            $stmt = $this->PDOprepare("DELETE FROM `subscribed_" . $type . "s` WHERE `" . $type . "id` = ? AND `userid` = ?;");
            return $stmt->execute([$preferenceId, $userId]);
        } else {
            return false;
        }
    }
    /**
     * Fetch media URLs
     */
    public function fetchMediaUrlsPerArticleId(int $id): PDOStatement
    {
        $sql_string = "SELECT * FROM `media_links` WHERE `articleid` = $id;";
        return $this->PDOquery($sql_string);
    }

    /**
     * Fetch Users Articles
     * User DB query
     * @param array $subscribedList List of Sources user follows
     * @param int $timeInterval time interval for query
     * @return PDOStatement Query Result
     */
    public function fetchUserSubscribedArticles(array $subscribedList = array(), array $topicsList = array(), int $timeInterval, int $offset)
    {
        if ($subscribedList === array() && $topicsList === array()) {
            return null;
        }
        $limit = 10;
        $offset = ($offset - 1) * 10;
        // $sql_string = "SELECT `a`.*, `s`.`reference`, `s`.`type`, `s`.`screenname`, `s`.`imagesource`, `ml`.`url` FROM `articles` AS `a`
        //                JOIN `sources` AS `s` ON `s`.`id` = `a`.`sourceid` 
        //                LEFT JOIN `articles_topics` AS `at` ON `a`.`id` = `at`.`articleid`
        //                LEFT JOIN `media_links` AS `ml` ON `a`.`id` = `ml`.`articleid`
        //                LEFT JOIN `topics` AS `t` ON `at`.`topicid` = `t`.`id` 
        //                WHERE `s`.`status` = 'active' AND (";
        $sql_string = "SELECT `a`.*, `s`.`reference`, `s`.`type`, `s`.`screenname`, `s`.`imagesource` FROM `articles` AS `a`
                       JOIN `sources` AS `s` ON `s`.`id` = `a`.`sourceid` 
                       LEFT JOIN `articles_topics` AS `at` ON `a`.`id` = `at`.`articleid`
                       LEFT JOIN `topics` AS `t` ON `at`.`topicid` = `t`.`id` 
                       WHERE `s`.`status` = 'active' AND (";
        $topicsIds = array();
        $sourceIds = array();
        if (count($subscribedList) > 0) {
            foreach ($subscribedList as $source) {
                $sourceIds[] = $source->getDbId();
            }
            $sql_source_ids = "(`a`.`sourceid` IN (" . join(',', $sourceIds) . "))";
        } else {
            $sql_source_ids = "";
        }
        if ((count($topicsList) > 0) && count($subscribedList) > 0) {
            $sql_link = ' OR ';
        } else {
            $sql_link = '';
        }
        if (count($topicsList) > 0) {
            foreach ($topicsList as $topic) {
                $topicsIds[] = $topic->dbId;
            }
            $sql_topic_ids = "(`at`.`topicid` IN (" . join(',', $topicsIds) . "))";
        } else {
            $sql_topic_ids = "";
        }

        $sql_where =  ") AND `creationdate` > " . $timeInterval . " 
                            ORDER BY `creationdate` DESC LIMIT " . $offset . "," . $limit;
        $sql_string = $sql_string . $sql_source_ids . $sql_link . $sql_topic_ids . $sql_where . ';';
        return $this->PDOquery($sql_string);

        return null;
    }
    /**
     * Fetch User by Username
     * User DB query
     * @param string $username of the user
     * @return PDOStatement Query Result
     */
    public function fetchUserByUsername(string $username): PDOStatement
    {
        $sql_string = "SELECT * FROM `users` WHERE `username` = '$username'";
        return $this->PDOquery($sql_string);
    }

    public function fetchUserById(int $id): PDOStatement
    {
        $sql_string = "SELECT * FROM `users` WHERE `id` = $id";
        return $this->PDOquery($sql_string);
    }
    /**
     * Insert User by Username
     * User DB query
     * @param string $username of the user
     * @return bool return True on success
     */
    public function insertUser(string $username, string $givenname = ''): bool
    {
        $stmt = $this->PDOprepare("INSERT INTO `users` (`username`, `givenname`) VALUES (?, ?)");
        return $stmt->execute([$username, $givenname]);
    }

    public function fetchTopicNameById(int $id): PDOStatement
    {
        $id = intval($id);
        $sql_string = "SELECT `name` FROM `topics` WHERE `id` = $id";
        return $this->PDOquery($sql_string);
    }
    public function updateLastLogin(string $username): bool
    {
        $timestamp = time();
        $stmt = $this->PDOprepare(
            "UPDATE `users` SET `lastlogin`= $timestamp  WHERE username = '$username';"
        );
        return $stmt->execute();
    }
    /** END User QUERIES */
    /** ADMIN QUERIES */
    public function updateSourceStatusById(int $sourceId, int $adminId): bool
    {
        $id = intval($sourceId);
        $sql_string = "SELECT `status` FROM sources WHERE `id` = $id";
        $logDescription = new stdClass;
        $logDescription->action = 'Source Status Update';
        $logDescription->sourceId = $id;
        $fetch = $this->PDOquery($sql_string);
        $fetched = $fetch->fetch();
        $stmt = $this->PDOprepare("UPDATE `sources` SET `status`=:newstatus WHERE `id`= :id");
        if ($fetched['status'] === 'suspended') {
            $newstatus = 'active';
        } else {
            $newstatus = 'suspended';
        }
        $logDescription->newstatus = $newstatus;
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('newstatus', $newstatus, PDO::PARAM_STR);
        $success = $stmt->execute();
        $logDescription->success = $success;
        $logDescription = json_encode($logDescription);
        $now = time();
        // $this->insertAdminLog($adminId, $now, $logDescription);
        return $success;
    }
    public function updateTopicStatusById(int $topicId, int $adminId): bool
    {
        $id = intval($topicId);
        $sql_string = "SELECT `status` FROM `topics` WHERE `id` = $id";
        $logDescription = new stdClass;
        $logDescription->action = 'Topic Status Update';
        $logDescription->topicId = $id;
        $fetch = $this->PDOquery($sql_string);
        $fetched = $fetch->fetch();
        $stmt = $this->PDOprepare("UPDATE `topics` SET `status`=:newstatus WHERE `id`= :id");
        if ($fetched['status'] === 'suspended') {
            $newstatus = 'active';
        } else {
            $newstatus = 'suspended';
        }
        $logDescription->newstatus = $newstatus;
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('newstatus', $newstatus, PDO::PARAM_STR);
        $success = $stmt->execute();
        $logDescription->success = $success;
        $logDescription = json_encode($logDescription);
        $now = time();
        // $this->insertAdminLog($adminId, $now, $logDescription);
        return $success;
    }
    // might be merged with updatetopicbyid
    public function updateSourceById(array $post, int $adminId): bool
    {
        $id = intval($post['id']) ?? false;
        if (!$id) {
            return false;
        }
        unset($post['id']);
        $updateArray = array();
        foreach ($post as $key => $value) {
            $updateArray[] = array($key, $value) ?? null;
        }
        foreach ($updateArray as $item) {
            if ($item === null) {
                continue;
            }
            $colName = $item[0];
            $newValue = $item[1];
            $stmt = $this->PDOprepare("UPDATE `sources` SET `$colName` = :newvalue WHERE `id` = :id;");
            $stmt->bindValue('newvalue', $newValue, PDO::PARAM_STR);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        // $this->insertAdminLog($adminId, time(), 'sources', 'update', $id);
        return true;
    }
    // might be merged with updatesourcesbyid
    public function updateTopicById(array $post, int $adminId): bool
    {
        $id = intval($post['id']) ?? false;
        if (!$id) {
            return false;
        }
        unset($post['id']);
        $updateArray = array();
        foreach ($post as $key => $value) {
            $updateArray[] = array($key, $value) ?? null;
        }
        print_r($post);
        foreach ($updateArray as $item) {
            if ($item === null) {
                continue;
            }
            $colName = $item[0];
            $newValue = $item[1];
            $stmt = $this->PDOprepare("UPDATE `topics` SET `$colName` = :newvalue WHERE `id` = :id;");
            $stmt->bindValue('newvalue', $newValue, PDO::PARAM_STR);
            $stmt->bindValue('id', $id, PDO::PARAM_INT);
            $stmt->execute();
        }
        // $this->insertAdminLog($adminId, time(), 'sources', 'update', $id);
        return true;
    }
    public function doesSourceExist(string $reference = null, string $type = null): bool
    {
        $reference = $reference ?? false;
        $type = $type ?? false;
        if (!$reference || !$type) {
            return false;
        }
        $sql_string = "SELECT * FROM `sources` WHERE `reference` = '$reference' AND `type` = '$type';";
        $fetched = $this->PDOquery($sql_string)->fetchAll();
        $fetchedLenght = count($fetched);
        if($fetchedLenght != 0){
            return true;
        }
        return false;
    }
    public function insertSource(array $post, int $adminId): bool
    {
        $reference = $post['reference'] ?? false;
        $screenname = $post['screenname'] ?? false;
        $type = $post['type'] ?? false;
        $status = $post['status'] ?? false;
        if (!$reference || !$screenname || !$type || !$status) {
            return false;
        }
        if($this->doesSourceExist($reference, $type)){
            return false;
        }
        $stmt = $this->PDOprepare("INSERT INTO `sources`( `reference`, `screenname`, `type`, `status`) VALUES (:reference, :screenname, :type, :status);");
        $stmt->bindValue('reference', $reference, PDO::PARAM_STR);
        $stmt->bindValue('screenname', $screenname, PDO::PARAM_STR);
        $stmt->bindValue('type', $type, PDO::PARAM_STR);
        $stmt->bindValue('status', $status, PDO::PARAM_STR);
        $success = $stmt->execute();
        if ($success) {
            // $this->insertAdminLog($adminId, time(), 'topics', 'add', intval($this->PDOgetlastinsertid()));
            return true;
        }
        return false;
    }
    public function insertTopic(array $post, int $adminId): bool
    {
        $name = $post['reference'] ?? false; //reference because of how its being posted from mdoal form
        $description = $post['description'] ?? false;
        $status = $post['status'] ?? false;
        if (!$name || !$description || !$status) {
            return false;
        }
        $stmt = $this->PDOprepare("INSERT INTO `topics`(`name`, `description`, `status`) VALUES (:name, :description, :status);");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        $stmt->bindValue('description', $description, PDO::PARAM_STR);
        $stmt->bindValue('status', $status, PDO::PARAM_STR);
        $success = $stmt->execute();
        if ($success) {
            // $this->insertAdminLog($adminId, time(), 'topics', 'add', intval($this->PDOgetlastinsertid()));
            return true;
        }
        return false;
    }
    public function insertAdminLog(int $adminId, int $time, string $target, string $action, int $targetid): bool
    {
        $stmt = $this->PDOprepare(
            "INSERT INTO `admin_logs` 
            (`userid`, `creationdate`, `target`, `action`, `targetid`) 
            VALUES 
            ( ?, ?, ?, ?, ?)"
        );
        return $stmt->execute([$adminId, $time, $target, $action, $targetid]);
    }
    public function fetchAdminLog(): PDOStatement
    {
        $sql_string = "SELECT * FROM `admin_logs` ORDER BY `creationdate` DESC LIMIT 20 ";
        return $this->PDOquery($sql_string);
    }
    /** END ADMIN QUERIES */
    /** SEARCH QUERIES */
    public function searchSourcesByReferenceOrName(string $term = null)
    {
        if (isset($term)) {
            // create prepared statement
            $sql = "SELECT * FROM `sources` WHERE (`screenname` LIKE :term OR reference LIKE :term) AND `status` = 'active'";
            $stmt = $this->PDOprepare($sql);
            $term = $term . '%';
            // bind parameters to statement
            $stmt->bindParam(":term", $term);
            // execute the prepared statement
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return $stmt->fetchAll();
            } else {
                return array();
            }
        }
    }
    /** END SEARCH QUERIES */
}
