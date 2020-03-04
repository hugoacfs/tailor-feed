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
            $connection = new PDO($this->hostname, $this->username, $this->password);
            $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection = $connection;
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
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
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
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
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
        }
    }
    /**
     * Internal execute method
     * @param PDOStatement $stmt pdo statement prepared to execute
     * @return bool Execute Result
     */
    private function PDOexecute(PDOStatement $stmt): bool
    {
        try {
            return ($stmt)->execute();
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
        }
    }
    /**
     * Internal execute method
     * @param PDOStatement $stmt pdo statement prepared to execute
     * @return bool Execute Result
     */
    private function PDOfetchAll(PDOStatement $stmt): array
    {
        try {
            return ($stmt)->fetchAll();
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
        }
    }
    private function ExecuteAndFetchArray(PDOStatement $stmt): array
    {
        $status = $this->PDOexecute($stmt);
        switch ($status) {
            case true:
                return $this->PDOfetchAll($stmt);
            default:
                return array();
        }
    }
    /**
     * Public lastInsertId method
     * @return int of the last inserted ID
     */
    public function PDOgetlastinsertid(): int
    {
        try {
            return intval(($this->connection)->lastInsertId());
        } catch (PDOException $ex) {
            handleException($ex);
        } catch (Exception $ex) {
            handleException($ex);
        }
    }
    /**
     * DB CONFIGURATION QUERIES
     */
    public function fetchConfiguration(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `config`;");
        return $this->ExecuteAndFetchArray($stmt);
    }
    public function fetchSourcesConfiguration(string $type): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `sources_config` WHERE `type` = '$type';");
        return $this->ExecuteAndFetchArray($stmt);
    }
    //END OF CONFIG QUERIES

    // DB QUERIES for class Article:
    /**
     * Fetches Article matching the UniqueId
     * Article DB query
     * @param string $matchingUniqueId Unique Id of Article
     * @return PDOStatement Query Result
     */
    public function fetchArticleByUniqueId(string $matchingUniqueId): array
    {
        $stmt = $this->PDOprepare(
            "SELECT * FROM `articles` 
            WHERE `uniqueidentifier` = :matchingUniqueId;"
        );
        $stmt->bindValue('matchingUniqueId', $matchingUniqueId, PDO::PARAM_STR);
        return $this->ExecuteAndFetchArray($stmt);
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
    public function insertNewArticleEntry(int $sourceid, string $uniqueidentifier, int $creationdate, string $body): bool
    {
        $stmt = $this->PDOprepare(
            "INSERT INTO `articles` 
            (`sourceid`, `uniqueidentifier`, `creationdate`, `body`) 
            VALUES 
            (:sourceid, :uniqueidentifier, :creationdate, :body);"
        );
        $stmt->bindValue('sourceid', $sourceid, PDO::PARAM_INT);
        $stmt->bindValue('uniqueidentifier', $uniqueidentifier, PDO::PARAM_STR);
        $stmt->bindValue('creationdate', $creationdate, PDO::PARAM_INT);
        $stmt->bindValue('body', $body, PDO::PARAM_STR);
        return $this->PDOexecute($stmt);
    }
    /**
     * Fetches the Article with max uniqueId 
     * Article DB query
     * @param int $id Unique Id of Article
     * @return PDOStatement Query Result
     */
    public function fetchLatestTwitterArticle(int $sourceid): array
    {
        $stmt = $this->PDOprepare(
            "SELECT MAX(a.uniqueidentifier) 
            FROM `articles` AS `a`
            JOIN `sources` AS `s` ON s.id=a.sourceid
            WHERE s.type = 'twitter' 
            AND s.id = :sourceid;"
        );
        $stmt->bindValue('sourceid', $sourceid, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * TODO: Complete Documenting
     */
    public function fetchAllTopics(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `topics`;");
        return $this->ExecuteAndFetchArray($stmt);
    }

    /**
     * TODO: Complete Documenting
     */
    public function fetchAllActiveTopics(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `topics` WHERE `status` = 'active';");
        return $this->ExecuteAndFetchArray($stmt);
    }

    public function fetchTopicId(string $name): array
    {
        $stmt = $this->PDOprepare("SELECT `id` FROM `topics` WHERE `name` = :name;");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        return $this->ExecuteAndFetchArray($stmt);
    }

    public function fetchMediaLinksArticle(int $id): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `media_links` WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
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
                (:articleid, :topicid);"
            );
            $stmt->bindValue('articleid', $articleid, PDO::PARAM_INT);
            $stmt->bindValue('topicid', $topicid, PDO::PARAM_INT);
            $this->PDOexecute($stmt);
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
                (`articleid`, `url`, `type`) 
                VALUES 
                (:articleid, :url, :type);"
            );
            $url = $m['url'];
            $type = $m['type'];
            $stmt->bindValue('articleid', $articleid, PDO::PARAM_INT);
            $stmt->bindValue('url', $url, PDO::PARAM_STR);
            $stmt->bindValue('type', $type, PDO::PARAM_STR);
            $this->PDOexecute($stmt);
        }
    }


    /** END Article QUERIES */
    /** DB QUERIES for class Source: */
    /**
     * Fetches All Sources from DB 
     * Source DB query
     * @return PDOStatement Query Result
     */
    public function fetchAllSources(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM sources;");
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * Fetches All Sources from DB 
     * Source DB query
     * @return PDOStatement Query Result
     */
    public function fetchAllActiveSources(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM sources WHERE `status` = 'active';");
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * Counts All Sources from DB 
     * Source DB query
     * @return int Query Result
     * @deprecated
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
    public function fetchSourceReferenceById(int $id): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM sources WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    /** END Source QUERIES */
    /**DB QUERIES FOR class Twitter */
    /**
     * Fetches Sources of specified Type 
     * Source DB query
     * @param string $type type of source
     * @return PDOStatement Query Result
     */
    public function fetchAllSourcesByType(string $type, $active = null): array
    {
        switch ($active) {
            case 'active':
                $stmt = $this->PDOprepare(
                    "SELECT * FROM sources 
                    WHERE `type` = :type 
                    AND `status` = 'active' ;"
                );
            default:
                $stmt = $this->PDOprepare(
                    "SELECT * FROM sources 
                    WHERE `type` = :type;"
                );
        }
        $stmt->bindValue('type', $type, PDO::PARAM_STR);
        return $this->ExecuteAndFetchArray($stmt);
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
        $stmt = $this->PDOprepare("UPDATE `sources` SET `$field` = :value WHERE `id` = :id");
        $stmt->bindValue('value', $value, PDO::PARAM_STR);
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->PDOexecute($stmt);
    }
    /** END Twitter QUERIES */
    /**DB QUERIES FOR class User */
    /**
     * Fetches pages preferences by userId
     * User DB query
     * @param int $userid Id of Source
     * @return PDOStatement Query Result
     */
    public function fetchUserPreferencesByUserId(int $userid): array
    {
        $stmt = $this->PDOprepare(
            "SELECT * FROM `subscribed_sources` AS `ss` 
        JOIN `sources` AS `s` ON `s`.`id` = `ss`.`sourceid` 
        WHERE `userid` = :userid 
        ORDER BY `s`.`reference`;"
        );
        $stmt->bindValue('userid', $userid, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * Fetches topics preferences by userId
     * User DB query
     * @param int $userid Id of Source
     * @return PDOStatement Query Result
     */
    public function fetchUserTopicsByUserId(int $userid): array
    {
        $stmt = $this->PDOprepare(
            "SELECT * FROM `subscribed_topics` AS `st` 
            JOIN `topics` AS `t` ON `t`.`id` = `st`.`topicid`
            WHERE `userid` = :userid AND `t`.`status` = 'active' 
            ORDER BY `t`.`name`;"
        );
        $stmt->bindValue('userid', $userid, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * User preferences CRD method
     * User DB query

     * @return array Query Result for select query
     * @return bool for insert and delete returns true or false
     */
    public function userPreferencesCrudQuery(string $type, int $preferenceid, int $userid, string $action)
    {
        $id = $preferenceid;
        switch ($action) {
            case 'select':
                $stmt = $this->PDOprepare(
                    "SELECT `id` FROM `subscribed_" . $type . "s` 
                WHERE `" . $type . "id` = :id 
                AND `userid` = :userid ;"
                );
                continue;
            case 'insert':
                $stmt = $this->PDOprepare(
                    "INSERT INTO `subscribed_" . $type . "s` (`" . $type . "id`, `userid`) 
                    VALUES (:id, :userid);"
                );
                continue;
            case 'delete':
                $stmt = $this->PDOprepare(
                    "DELETE FROM `subscribed_" . $type . "s` 
                    WHERE `" . $type . "id` = :id 
                    AND `userid` = :userid;"
                );
                continue;
        }
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        $stmt->bindValue('userid', $userid, PDO::PARAM_INT);
        switch ($action) {
            case 'select':
                return $this->ExecuteAndFetchArray($stmt);
            case 'insert':
            case 'delete':
                return $this->PDOexecute($stmt);
            default:
                return false;
        }
    }
    /**
     * Fetch media URLs
     */
    public function fetchMediaUrlsPerArticleId(int $articleid): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `media_links` WHERE `articleid` = :articleid;");
        $stmt->bindValue('articleid', $articleid, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
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
        $sql_string = "SELECT `a`.*, `s`.`reference`, `s`.`type`, `s`.`screenname`, `s`.`imagesource`
            FROM `articles` AS `a`
                JOIN `sources` AS `s` ON `s`.`id` = `a`.`sourceid`
                LEFT JOIN `articles_topics` AS `at` ON `a`.`id` = `at`.`articleid`
                LEFT JOIN `topics` AS `t` ON `at`.`topicid` = `t`.`id`
            WHERE `s`.`status` = 'active' AND ";
        $topicsIds = array();
        $sourceIds = array();
        $sourceTopicsIds = [];

        if (count($subscribedList) > 0) {
            foreach ($subscribedList as $source) {
                $sourceIds[] = $source->getDbId();
            }
            $sourceTopicsIds[] = " `a`.`sourceid` IN (" . join(',', $sourceIds) . ") ";
        }

        if (count($topicsList) > 0) {
            foreach ($topicsList as $topic) {
                $topicsIds[] = $topic->dbId;
            }
            $sourceTopicsIds[] = " `at`.`topicid` IN (" . join(',', $topicsIds) . ") ";
        }

        $sql_sourceTopics = " ( " . join(' OR ', $sourceTopicsIds) . " ) ";
        $sql_where =  " AND `creationdate` > " . $timeInterval . " 
                            ORDER BY `creationdate` DESC LIMIT " . $offset . "," . $limit;
        $sql_string .= $sql_sourceTopics . $sql_where . ';';
        return $this->PDOquery($sql_string);
    }
    /**
     * Fetch User by Username
     * User DB query
     * @param string $username of the user
     * @return PDOStatement Query Result
     */
    public function fetchUserByUsername(string $username): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `users` WHERE `username` = :username;");
        $stmt->bindValue('username', $username, PDO::PARAM_STR);
        return $this->ExecuteAndFetchArray($stmt);
    }

    public function fetchUserById(int $id): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `users` WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    /**
     * Insert User by Username
     * User DB query
     * @param string $username of the user
     * @return bool return True on success
     */
    public function insertUser(string $username, string $givenname = '', string $password = null): bool
    {
        $stmt = $this->PDOprepare(
            "INSERT INTO `users` (`username`, `givenname`, `password`) 
            VALUES (:username, :givenname, :password);"
        );
        $stmt->bindValue('username', $username, PDO::PARAM_STR);
        $stmt->bindValue('givenname', $givenname, PDO::PARAM_STR);
        $stmt->bindValue('password', $password, PDO::PARAM_STR);
        return $this->PDOexecute($stmt);
    }
    public function fetchTopicNameById(int $id): array
    {
        $stmt = $this->PDOprepare("SELECT `name` FROM `topics` WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->ExecuteAndFetchArray($stmt);
    }
    public function updateLastLogin(string $username): bool
    {
        $lastlogin = time();
        $stmt = $this->PDOprepare(
            "UPDATE `users` SET `lastlogin`= :lastlogin  WHERE username = :username;"
        );
        $stmt->bindValue('username', $username, PDO::PARAM_STR);
        $stmt->bindValue('lastlogin', $lastlogin, PDO::PARAM_INT);
        return $this->PDOexecute($stmt);
    }
    /** END User QUERIES */
    /** ADMIN QUERIES */
    //TODO:REVIEW
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
    // TODO:REVIEW
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
    //TODO:REVIEW
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
    //TODO:REVIEW
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
        foreach ($updateArray as $item) {
            if ($item === null) {
                continue;
            }
            $colName = $item[0];
            $newValue = $item[1];
            if ($colName === 'name') {
                $newValue = preg_replace("/[^a-zA-Z0-9]/", "", $newValue);
            }
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
        $stmt = $this->PDOprepare(
            "SELECT * FROM `sources` 
            WHERE `reference` = :reference 
            AND `type` = :type;"
        );
        $stmt->bindValue('reference', $reference, PDO::PARAM_STR);
        $stmt->bindValue('type', $type, PDO::PARAM_STR);
        $fetchedArray = $this->ExecuteAndFetchArray($stmt);
        if ($fetchedArray) {
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
        if ($this->doesSourceExist($reference, $type)) {
            return false;
        }
        $stmt = $this->PDOprepare("INSERT INTO `sources`( `reference`, `screenname`, `type`, `status`) VALUES (:reference, :screenname, :type, :status);");
        $stmt->bindValue('reference', $reference, PDO::PARAM_STR);
        $stmt->bindValue('screenname', $screenname, PDO::PARAM_STR);
        $stmt->bindValue('type', $type, PDO::PARAM_STR);
        $stmt->bindValue('status', $status, PDO::PARAM_STR);
        return $this->PDOexecute($stmt);
        // $success = $this->PDOexecute($stmt);
        // if ($success) {
        //     // $this->insertAdminLog($adminId, time(), 'topics', 'add', intval($this->PDOgetlastinsertid()));
        //     return true;
        // }
        // return false;
    }
    public function insertTopic(array $post, int $adminId): bool
    {
        $name = $post['name'] ?? false; //reference because of how its being posted from modal form
        if ($name) {
            $name = preg_replace("/[^a-zA-Z0-9]/", "", $name);
        }
        $description = $post['description'] ?? 'No description available.';
        $status = $post['status'] ?? false;
        if (!$name || !$description || !$status) {
            return false;
        }
        $stmt = $this->PDOprepare("INSERT INTO `topics`(`name`, `description`, `status`) VALUES (:name, :description, :status);");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        $stmt->bindValue('description', $description, PDO::PARAM_STR);
        $stmt->bindValue('status', $status, PDO::PARAM_STR);
        return $this->PDOexecute($stmt);
        // $success = $this->PDOexecute($stmt);
        // if ($success) {
        //     // $this->insertAdminLog($adminId, time(), 'topics', 'add', intval($this->PDOgetlastinsertid()));
        //     return true;
        // }
        // return false;
    }

    public function insertAdminLog(int $userid, int $creationdate, string $target, string $action, int $targetid): bool
    {
        $stmt = $this->PDOprepare(
            "INSERT INTO `admin_logs` 
            (`userid`, `creationdate`, `target`, `action`, `targetid`) 
            VALUES 
            (:userid, :creationdate, :target, :action, :targetid);"
        );
        $stmt->bindValue('userid', $userid, PDO::PARAM_INT);
        $stmt->bindValue('creationdate', $creationdate, PDO::PARAM_INT);
        $stmt->bindValue('target', $target, PDO::PARAM_STR);
        $stmt->bindValue('action', $action, PDO::PARAM_STR);
        $stmt->bindValue('targetid', $targetid, PDO::PARAM_INT);
        return $this->PDOexecute($stmt);
    }
    public function fetchAdminLog(): array
    {
        $stmt = $this->PDOprepare("SELECT * FROM `admin_logs` ORDER BY `creationdate` DESC LIMIT 20;");
        return $this->ExecuteAndFetchArray($stmt);
    }
    public function fetchAllArticlesAndSources(int $page, int $max, $sourceid = null): array
    {
        try {
            if ($sourceid) $sourceid = intval($sourceid);
            if ($max != 0) {
                $limit = intval($max);
                $offset = intval(($page - 1) * $max);
                $sql_limit = "LIMIT $offset,$limit";
            }
            $sql_where = '';
            if ($sourceid) {
                $sql_where = " AND `s`.`id` = :sourceid ";
            }
            $stmt = $this->PDOprepare(
                "SELECT `a`.*, `s`.`reference`, `s`.`screenname`, `s`.`type`, `s`.`status` 
        FROM `articles` AS `a` 
        JOIN `sources` AS `s` 
        WHERE `s`.`id` = `a`.`sourceid` " . $sql_where . " 
        ORDER BY `creationdate` 
        DESC $sql_limit; "
            );
            if ($sourceid) {
                $stmt->bindValue('sourceid', $sourceid, PDO::PARAM_INT);
            }
            $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
            return $this->ExecuteAndFetchArray($stmt);
        } catch (Exception $ex) {
            $stmt = $this->PDOprepare(
                "SELECT `a`.*, `s`.`reference`, `s`.`screenname`, `s`.`type`, `s`.`status` 
        FROM `articles` AS `a` 
        JOIN `sources` AS `s` 
        WHERE `s`.`id` = `a`.`sourceid` 
        ORDER BY `creationdate` 
        DESC LIMIT 10,1; "
            );
            return $this->ExecuteAndFetchArray($stmt);
        }
    }
    public function deleteArticleById(int $id): bool
    {
        $stmt = $this->PDOprepare("DELETE FROM `articles` WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->PDOexecute($stmt);
    }
    public function deleteTopicById(int $id): bool
    {
        $stmt = $this->PDOprepare("DELETE FROM `topics` WHERE `id` = :id;");
        $stmt->bindValue('id', $id, PDO::PARAM_INT);
        return $this->PDOexecute($stmt);
    }
    /** END ADMIN QUERIES */
    /** SEARCH QUERIES */
    /**
     * @deprecated
     */
    public function searchSourcesByReferenceOrName(string $term = null): array
    {
        if (isset($term)) {
            // create prepared statement
            $stmt = $this->PDOprepare(
                "SELECT * FROM `sources` 
                WHERE (`screenname` LIKE :term OR reference LIKE :term) 
                AND `status` = 'active';"
            );
            $term = $term . '%';
            // bind parameters to statement
            $stmt->bindParam(":term", $term);
            // execute the prepared statement and return result
            return $this->ExecuteAndFetchArray($stmt);
        }
    }
    /** END SEARCH QUERIES */
    /** CRON QUERIES */
    public function updateLastCronTime(string $type): bool
    {
        $stmt = $this->PDOprepare("UPDATE `sources_config` SET `value` = :time WHERE `type` = :type AND `name` = 'last_cron';");
        $stmt->bindValue('time', time(), PDO::PARAM_STR);
        $stmt->bindValue('type', $type, PDO::PARAM_STR);
        return $this->PDOexecute($stmt);
    }
    /** END CRON QUERIES */
}
