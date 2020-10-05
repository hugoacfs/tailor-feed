<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
// Page CLASS
class Page
{
    /**
     * Mustache file to load
     * 
     */
    private $location = 'page';
    public $modals = [
        [
            "type" => "pages",
            "title" => "Following"
        ],
        [
            "type" => "topics",
            "title" => "Topics"
        ]
    ];

    function __construct(string $target, string $title = 'theFeed')
    {
        
        global $_SESSION;
        $this->title = $title;
        $this->hasHead = true;
        $this->hasNav = true;
        $this->hasFooter = true;
        switch ($target) {
            case 'feed':
                $this->hasFeed = true;
                break;
            case 'login':
                $this->hasLogin = true;
                break;
            case '404':
                $this->has404 = true;
                break;
            case 'index':
            case 'home':
            case 'default':
                $this->hasLogin = true;
                break;
        }
        $this->signedIn = $_SESSION['signedIn'];
        if ($this->signedIn) {
            $this->userName = $_SESSION['userName'];
            $this->givenName = $_SESSION['givenName'];
            $this->user = new User($this->userName);
            $this->isAdmin = ($_SESSION['role'] === 'a');
            if ($this->hasFeed) {
                $this->articles = $this->user->constructArticles()['articles'];
            }
        }
        $this->renderFromTemplate();
    }

    public function renderFromTemplate()
    {
        global $CFG;
        $m = new Mustache_Engine(
            ['loader' => new Mustache_Loader_FilesystemLoader($CFG->dirroot . '/templates')]
        );
        debug_to_console((array) $this);
        echo $m->render($this->location, (array) $this);
    }
}
