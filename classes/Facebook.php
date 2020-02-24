<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
// FACEBOOK CLASS
class Facebook extends Source
{
    /**
     * Url refers to the url of the Source,
     * which for Facebook it should always be
     * 'https://www.facebook.com/'.
     * @var string $url
     */
    public $url = 'https://www.facebook.com/';
    /**
     * Type of Source object as in DB.
     * e.g. Facebook is 'facebook', as in the database.
     * @var string $type
     */
    public $type = 'facebook';
    /**
     * The string value which holds the settings used for Graph API queries.
     * Change this string according to the instructions found here: [url]
     * @var string $requestExtraSettings
     */
    private $requestExtraSettings = '';
    /**
     * Builds an array of Articles belonging to the Facebook object.
     * Populates $this->articles with the relevant Article objects, using [facebook api].
     * @return void
     */
    public function buildArticles(): void
    {
    }
    /**
     * It returns an array containing all sources as a Facebook object from the DB.
     * @return array
     */
    public static function getAllSources(): array
    {
        return array();
    }
    /**
     * Performs a query to Graph API
     * Takes in the URL to the API and the required settings, returns false on failure, JSON on success.
     * @param string $apiUrl URL to the API to query Graph from.
     * @param string $getfield string which contains the settings for the Graph API query.
     * @return string $json_data
     * @return bool If it fails, returns false.
     */
    private static function facebookQuery(string $apiUrl, string $getfield)
    {
        return false;
    }
    /**
     * Updates the DB details for all of the Facebook sources.
     * Fetches all facebook sources, then for each source it gathers Facebook data, then updates the DB's fields.
     * @return bool Returns true on success.
     */
    public static function updateSourcesDetails(): bool
    {
        global $DB;
        $type = 'facebook';
        $fetched = $DB->fetchAllSourcesByType($type);
        $fieldImage = 'imagesource';
        $fieldName = 'screenname';
        foreach ($fetched as $row) {
            $facebookData = Facebook::getSourceData($row['reference']);
            $valueImage = $facebookData->profile_image_url_https;
            $valueName = $facebookData->name;
            $id = $row['id'];
            $successName = $DB->updateSourcesFieldById($fieldName, $valueName, $id);
            $successImage = $DB->updateSourcesFieldById($fieldImage, $valueImage, $id);
            if (!$successName or !$successImage) {
                return false;
            }
        }
        return true;
    }
    /**
     * Fetches the data for a Facebook page for $reference given.
     * Makes the Graph API request required to get public account data.
     * @param string $reference
     * @return object $facebookData
     */

    protected static function getSourceData(string $reference): object
    {
        $apiUrl = '';
        $getfield = '' . $reference;
        $facebookData = Facebook::facebookQuery($apiUrl, $getfield);
        return $facebookData;
    }
}
