<?php
define('CONFIG_PROTECTION', false);
$title = 'Bake Cookies';
$pageId = 'bake';
require_once(__DIR__ . '/config.php');
session_start();
require_once($CFG->dirroot . '/inc/php/authenticate.php');
require_once($CFG->dirroot . '/inc/html/head.php');
require_once($CFG->dirroot . '/inc/html/nav.php');

$userId = $_SESSION['userId'];
$toastName = 'test3';
$header = 'This is a test notification';
$body = 'Hello ' . $_SESSION['givenName']. ', it\'s working!';
// makeSomeToast($userId, $body, $toastName, $header);
$lastArticleUniqueId = Article::getLatestArticleUId(5);
echo $lastArticleUniqueId;
if ($lastArticleUniqueId === 0) $lastArticleUniqueId = 1132305067937714178;
echo $lastArticleUniqueId;