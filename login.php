<?php
define('CONFIG_PROTECTION', false);
$title = 'UoC Community News';
$pageId = 'login';
require_once __DIR__ .'/config.php';
if (!isset($_SESSION['username'])) {
    signMeIn($CFG->authmethod);
    header('Location: timeline.php');
}
die();
