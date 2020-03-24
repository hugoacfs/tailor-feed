<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 * Source class
 */
class Source
{
    /**
     * Database ID of Source object. 
     * Or: 'id' in 'sources'
     *  @var int $dbId
     */
    public $dbId;
    /**
     * Url refers to the url of the Source. 
     * e.g. Twitter's url would be 'https://twitter.com/'
     * @var string $url
     */
    protected $url;
    /**
     * Reference of the Source object as in DB.
     * e.g. @chiuni for Twitter would be 'chiuni'
     * @var string $reference
     */
    protected $reference;
    /**
     * Full name of the Source object.
     * e.g. @chiuni for Twitter would be 'University of Chichester'
     * @var string $name
     */
    protected $name;
    /**
     * Type of Source object as in DB.
     * e.g. Twitter is 'twitter', as in the database.
     * @var string $type
     */
    protected $type;
    /**
     * Url to the image of the Source object.
     * e.g. would be something like 'https://pbs.twimg.com/profile_images/1170993848321024001/5RIUDlRK_normal.jpg'
     * @var string $imageSource
     */
    protected $imageSource;
    /**
     * Array containing Article objects, linked to this Source object.
     * @var array $articles
     */
    protected $articles = [];
    /**
     * Constructor for Sources.
     * Dynamic constructor, it will build the Source object depending on 
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
    public function getDbId(): int
    {
        return $this->dbId;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getUrl(): string
    {
        return $this->url;
    }
    public function getType(): string
    {
        return $this->type;
    }
    public function getReference(): string
    {
        return $this->reference;
    }
    public function getArticles(): array
    {
        return $this->articles;
    }
    /**
     * It returns an array containing all sources as a Source object from the DB.
     * @param bool $active if true it will only get sources which are not suspended from DB.
     * @return array $sources
     */
    public static function getAllSources(bool $active = false): array
    {
        global $DB;
        $sources = [];
        if ($active) $result = $DB->fetchAllActiveSources();
        else $result = $DB->fetchAllSources();
        foreach ($result as $row) {
            $type = $row['type'];
            $reference = $row['reference'];
            $id = $row['id'];
            $name = $row['screenname'];
            switch($type){
                case 'twitter':
                    $url = 'https://twitter.com/'.$reference;
                    break;
                default:
                    $url = '';
                    break;
            }
            $builder = array(
                'dbId' => $id,
                'reference' => strtolower($reference),
                'type' => $type,
                'name' => $name,
                'url' => $url
            );
            $sources[] = new Source($builder);
        }
        return $sources;
    }
    /**
     * It returns the ids of all sources in the DB as an array.
     * @return array All the DB ids of sources
     */
    public static function getAllSourcesIds(): array
    {
        global $DB;
        $sourcesIds = [];
        $fetched = $DB->fetchAllSources();
        foreach ($fetched as $row) $sourcesIds[] = $row['id'];
        return $sourcesIds;
    }
    /**
     * Updates all sources details.
     * Add to this method as required.
     * @return bool Returns true on success.
     */
    public static function updateAllSourcesDetails(): bool
    {
        $twitterSuccess = Twitter::updateSourcesDetails();
        // $rssSuccess = Rss::updateSourcesDetails(); //Exmaple of further implm.
        if ($twitterSuccess) return true;
        return false;
    }
}
