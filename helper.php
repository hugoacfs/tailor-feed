<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
/**
 *  An example CORS-compliant method.  It will allow any GET, POST, or OPTIONS requests from any
 *  origin.
 *
 *  In a production environment, you probably want to be more restrictive, but this gives you
 *  the general idea of what is involved.  For the nitty-gritty low-down, read:
 *
 *  - https://developer.mozilla.org/en/HTTP_access_control
 *  - http://www.w3.org/TR/cors/
 *
 */
function cors()
{
    global $CFG;
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        if (!in_array($_SERVER['HTTP_ORIGIN'], $CFG->authorised_cors_array)) exit('Your site is not whitelisted!');
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }

    //echo "You have CORS!";
}
// returns X weeks ago from Now
function weeksAgo($numberOfWeeks)
{
    $now = time();
    $oneWeekAgo = $now - ($numberOfWeeks * (60 * 60 * 24 * 7));
    return $oneWeekAgo;
}

function timeAgo($time_ago)
{
    // $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed;
    $minutes    = round($time_elapsed / 60);
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400);
    if ($days >= 1) {
        return date('D d/m/Y G:i', $time_ago);
    }
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640);
    $years      = round($time_elapsed / 31207680);
    // Seconds
    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    else if ($minutes <= 60) {
        if ($minutes == 1) {
            return "one minute ago";
        } else {
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if ($hours <= 24) {
        if ($hours == 1) {
            return "an hour ago";
        } else {
            return "$hours hours ago";
        }
    }
}


// TURNS TEXT INTO TEXT WITH ANCHOR TAGS
function convertLinks($text): string
{
    if ($text != null) {
        $reg_exUrl = "/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        // Check if there is a url in the text
        if (preg_match($reg_exUrl, $text, $url)) {

            // make the urls hyper links
            return preg_replace($reg_exUrl, '<a title="$0" href="$0" target="uni_news" rel="nofollow" data-toggle="tooltip" data-placement="top">external link <i class="fas fa-external-link-alt"></i></a>', $text);
        } else {

            // if no urls in the text just return the text
            return $text;
        }
    } else {
        return '';
    }
}
function convertHashtags($text)
{
    $regex = "/#([a-zA-Z0-9_ー-]+)\b/";
    if (preg_match_all($regex, $text, $tag)) {
        $toReplace = '<a title="https://twitter.com/hashtag/$1" href="https://twitter.com/hashtag/$1" data-toggle="tooltip" data-placement="top" target="uni_news">$0</a>';
        return preg_replace($regex, $toReplace, $text);
    } else {

        // if no urls in the text just return the text
        return $text;
    }
}

function extractHashtags($text)
{
    $regex = "/#([a-zA-Z0-9_]+)\b/";
    $hashtag_set = [];
    $array = explode('#', $text);
    if (preg_match_all($regex, $text, $tag)) {
        foreach ($tag[1] as $t) {
            $hashtag_set[] = strtolower($t);
        }
    }
    return $hashtag_set;
}

function convertMentions($text)
{
    $regex = "/@([a-zA-Z0-9_]+)\b/";
    if (preg_match($regex, $text, $mention)) {
        return preg_replace($regex, '<a title="https://twitter.com/$1" href="https://twitter.com/$1" target="uni_news" data-toggle="tooltip" data-placement="top">$0</a>', $text);
    } else {

        // if no urls in the text just return the text
        return $text;
    }
}

/**  LOGGING IN & REDIRECTING USERS
 *
 */
function prepareUrlRedirect()
{
    if (isset($_SERVER['SCRIPT_NAME'])) {
        return $_SERVER['SCRIPT_NAME'];
    }
}
function isAdminLoggedIn()
{
    $role = $_SESSION['role'] ?? 'u';
    if (!isLoggedIn()) {
        return false;
    } elseif (($role === 'a')) {
        return true;
    } else {
        return false;
    }
}
function isLoggedIn()
{
    if (isset($_SESSION['signedIn'])) {
        return true;
    } else {
        return false;
    }
}
function redirectGuestToLogin()
{
    // header('Location: login.php?redirecturl=' . urlencode(prepareUrlRedirect()));
    header('Location: login.php');
}
function redirectUserToTimeline()
{
    // header('Location: login.php?redirecturl=' . urlencode(prepareUrlRedirect()));
    header('Location: timeline.php');
}

function getSubscribedIds($array)
{
    $listOfIds = array();
    if (!empty($array)) {
        foreach ($array as $item) {
            if (is_a($item, 'Source')) {
                $listOfIds[] = $item->getDbId();
            } else {
                $listOfIds[] = $item->dbId;
            }
        }
    }
    return $listOfIds;
}

function performAdminTask(string $action, array $actionArray, int $adminId): bool
{
    global $DB;
    switch ($action) {
        case 'update-source':
            return $DB->updateSourceById($actionArray, $adminId);
        case 'add-source':
            return $DB->insertSource($actionArray, $adminId);
        case 'update-topic':
            return $DB->updateTopicById($actionArray, $adminId);
        case 'add-topic':
            return $DB->insertTopic($actionArray, $adminId);
        case 'suspend-source':
        case 'activate-source':
            return $DB->updateSourceStatusById(intval($actionArray['id']), $adminId);
        case 'suspend-topic':
        case 'activate-topic':
            return $DB->updateTopicStatusById(intval($actionArray['id']), $adminId);
        case 'delete-article':
            return $DB->deleteArticleById(intval($actionArray['id']), $adminId);
        case 'delete-topic':
            return $DB->deleteTopicById(intval($actionArray['id']), $adminId);
        case 'update-config':
            return $DB->updateCronConfiguration($actionArray, $adminId);
        default:
            return false;
    }
}

function handleException($ex, $message = 'Please contact support to let us know about this problem.')
{
    global $CFG, $EXCEPTION;
    $EXCEPTION = new stdClass;
    $EXCEPTION->code = '500';
    $EXCEPTION->message = $message ?? 'Please contact support to let us know about this problem.';
    if ($CFG->debug_mode === 'on') {
        echo '<h5>' . $message . '</h5>';
        echo '<pre>';
        print_r($ex);
        echo '</pre>';
        $EXCEPTION->code = $ex->getCode();
        $EXCEPTION->message = $ex->getMessage();
    }

    if (php_sapi_name() != 'cli') include $CFG->dirroot . '/error.php';
    exit();
}
function contains(string $str, array $arr): bool
{
    foreach ($arr as $a) {
        if (stripos($str, $a) !== false) return true;
    }
    return false;
}

function restructureString(string $str): array
{
    $arr = explode('_', $str);
    $extractStr = array_shift($arr);
    $otherStr = implode('_', $arr);
    return array($extractStr, $otherStr);
}
