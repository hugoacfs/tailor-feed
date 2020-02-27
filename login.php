<?php
define('CONFIG_PROTECTION', false);
$title = 'UoC Community News';
$pageId = 'login';
require_once __DIR__ .'/config.php';
if (!isset($_SESSION['signedIn'])) {
    require_once $CFG->dirroot . '/inc/php/authenticate.php';;
    header('Location: timeline.php');
}
die();
