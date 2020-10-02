<?php
define('CONFIG_PROTECTION', false);
require_once(__DIR__ . '/config.php');
session_start();
// debug_to_console($_SESSION);
$pageUser = new User($_SESSION['userName'] ?? 'default');
$PAGE = [
    "pagetitle" => 'Test',
    "signedin" => $_SESSION['signedIn'] ?? false,
    "name" => $_SESSION['givenName'] ?? '',
    "isadmin" => ($_SESSION['role'] === 'a') ?? false,
    "modals" => [
        [
            "type" => "pages",
            "title" => "Following"
        ],
        [
            "type" => "topics",
            "title" => "Topics"
        ]
    ],
    "articles" => $pageUser->constructArticles()['articles']
];
$location = 'page';
echo renderFromTemplate($location, $PAGE);
