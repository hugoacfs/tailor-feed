<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 * This is the configuration class for the News API.
 */
$CFG = new stdClass();
$CFG->username = 'root';
$CFG->password = '';
$CFG->hostname = 'mysql:host=localhost;dbname=database_name';
$CFG->dirroot = __DIR__;
$CFG->authmethod = '';
$CFG->jsonkeys = array(
    'secret_key' => ""
);
$CFG->twtapisettings = array(
    'oauth_access_token' => "",
    'oauth_access_token_secret' => "",
    'consumer_key' => "",
    'consumer_secret' => ""
);
$CFG->fbapisettings = array(
    'client_id' => "",
    'client_secret' => "",
    'access_token' => ""
);
/**
 * The autoloader for the News API classes.
 */
spl_autoload_register(
    function ($class_name) {
        global $CFG;
        $CLASSES_DIR = $CFG->dirroot . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;  // or whatever your directory is
        $file = $CLASSES_DIR . $class_name . '.php';
        if (file_exists($file)) require $file;  // only include if file exists, otherwise we might enter some conflicts with other pieces of code which are also using the spl_autoload_register function
    }
);
/**
 * Global DB and CONNECTION objects.
 */
$DB = new Connection($CFG->hostname, $CFG->username, $CFG->password);
/**
 * Loading extra helper methods.
 * Custom methods added here.
 */
require_once $CFG->dirroot . '/api/helper.php';
