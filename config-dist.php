<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 * This is the CORE configuration class for the News API.
 */
$CFG = new stdClass();
/**
 * CORE DB configuration, and DIRECTORY configuration
 */
$CFG->username = 'root';
$CFG->password = '';
$CFG->hostname = 'mysql:host=localhost;dbname=thefeed';
$CFG->dirroot = __DIR__;
/**
 * The autoloader for the News API classes.
 */
define('CLASS_LOADER', true);
spl_autoload_register(
    function ($class_name) {
        global $CFG;
        $CLASSES_DIR = $CFG->dirroot . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR;  // or whatever your directory is
        $file = $CLASSES_DIR . $class_name . '.php';
        if (file_exists($file)) require_once($file);  // only include if file exists, otherwise we might enter some conflicts with other pieces of code which are also using the spl_autoload_register function
    }
);
/**
 * Loading extra helper methods.
 * Custom methods added here.
 */
require_once($CFG->dirroot . '/lib.php');
/**
 * Global DB and CONNECTION objects.
 */
$DB = new Connection($CFG->hostname, $CFG->username, $CFG->password);
unset($CFG->username);
unset($CFG->password);
unset($CFG->hostname);
/**
 * This is the MAIN configuration from DB.
 */
$config = $DB->fetchConfiguration();

/**
 * Populating MAIN configuration from DB
 */
foreach ($config as $c) {
    $components = (array) explode('_', $c['name']);
    $config_builder = '$CFG';
    foreach ($components as $key => $value) {
        $value = preg_replace('/[^a-z]/i','',$value); //removing everything but a-z
        $config_builder .=  '->' . $value;
    }  
    $config_builder .= ' = $setting;'; 
    $setting = $c['value'];
    eval($config_builder);
}