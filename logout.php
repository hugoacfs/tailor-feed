<?php
define('CONFIG_PROTECTION', false);
session_start();
$_SESSION = [];
unset($_SESSION);
// require __DIR__ . '/config.php';
// require_once('/var/simplesamlphp/lib/_autoload.php');
// $CFG->auth = new \SimpleSAML\Auth\Simple('default-sp');
// $CFG->auth->logout('/index.php');
// $CFG->session->cleanup();
session_destroy();
header('Location: index.php');
exit;
die();
