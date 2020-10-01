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
    $regex = "/#([a-zA-Z0-9_ー-]+)\b/";
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
function redirectUserToFeed()
{
    // header('Location: login.php?redirecturl=' . urlencode(prepareUrlRedirect()));
    header('Location: feed.php');
}

function getSubscribedIds(array $array): array
{
    $listOfIds = [];
    foreach ($array as $item) $listOfIds[] = $item->dbId;
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

    if (php_sapi_name() != 'cli') require_once($CFG->dirroot . '/error.php');
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
/**
 * @param array $bread is an array which contains all the necessary strings to populate the toast
 * @return string HTML of all toasts
 */
function serveToast(array $bread): string
{
    $serving = ''; //our return string of html toasts;
    foreach ($bread as $butter => $slice) {
        //each slice must consist of 3 parts: timestamp, header, body
        if (!isset($slice['header'])) continue;
        if (!isset($slice['timestamp'])) continue;
        if (!isset($slice['body'])) continue;
        $toast = '  <div id="' . $butter . '-toast" class="toast" data-autohide="false" data-cookie="' . $butter . '">
            <div class="toast-header">
              <img src="img/favicon.ico" class="rounded mr-2" style="max-width: 25px;" alt="icon">
              <strong class="mr-auto text-primary">' . $slice['header'] . '</strong>
              <small class="text-muted"> ' . timeAgo($slice['timestamp']) . '</small>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body bg-dark text-light">
              ' . $slice['body'] . '
            </div>
          </div>';
        $serving .= $toast; //add the toast to our serving
    }
    return $serving; //return our serving
}

/**
 * @param int $userId the id of the user which to create the cookies for
 * @param int $timestamp the time of message creation
 * @param 
 * @param 
 * @return 
 */
function makeSomeToast(int $userId, string $body, string $toastName, string $header = 'New notification', int $timestamp = -1)
{
    if ($userId == 0) {
        error_log('no user id provided to makeSomeToast on helper.php, skipping...');
        return;
    }
    if ($timestamp < 0) $timestamp = time();
    $myToast = [];
    if (isset($_COOKIE[$userId])) $myToast = unserialize($_COOKIE[$userId]);
    $bread = [];
    $bread['timestamp'] = strval($timestamp);
    $bread['header'] = $header;
    $bread['body'] = $body;
    $myToast[$toastName] = $bread;
    setcookie($userId, serialize($myToast), 0, '/');
}

function buildModal(string $type, string $title = ''): string
{
    $title = (!empty($title)) ? $title : ucwords($type);
    $modal = '  <div class="modal fade" id="' . $type . 'Modal" tabindex="-1" role="dialog" aria-labelledby="' . $type . 'Modal" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="' . $type . 'ModalCenteredLabel">' . $title . '</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Search</span>
                                            </div>
                                            <input id="search-area-' . $type . '" type="text" class="form-control search-me" placeholder="Example: chiuni" aria-label="Search">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <form id="' . $type . '-modal-form" class="text-center" style="color: #757575;" action="" method="POST">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                        </form>
                                        <!-- Form -->
                                    </div>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button form="' . $type . '-modal-form" name="submit' . $type . '" class="btn btn-primary" id="submit' . $type . '" type="submit' . $type . '">Save changes</button>
                            </div>
                        </div>
                    </div>
                </div>';
    return $modal;
}

function loadPreferences(string $type, string $userName): string
{
    $html = '';
    if (!isset($userName)) return 'No user.';
    $user = new User($userName);
    $userPrefList = $user->getPreferences($type);
    $allPreferences = [];
    $label = '';
    switch ($type) {
        case 'sources':
            $allPreferences = Source::getAllSources(true);
            $label = 'Account information';
            break;
        case 'topics':
            $allPreferences = Article::getAllTopics();
            $label = 'Topic Name';
            break;
    }
    $html .= '
    <div class="form-row">	
        <div class="col">	
            <!-- Handle -->	
            <div class="md-form">	
                <div class="row ">	
                    <div class="col-8 align-self-center">	
                        <label class="form-text text-muted text-left" for="preferences">' . $label . ':</label>	
                    </div>	
                    <div class="col-4 align-self-center">	
                        <label class="form-text text-muted text-left" for="preferences"> Subscribed: </label>	
                    </div>	
                </div>	
            </div>	
        </div>                               	
    </div>	
    <hr class="mb-0" />';
    $idsArray = getSubscribedIds($userPrefList);
    foreach ($allPreferences as $pref) {
        $id = $pref->dbId;
        $isInArray = in_array($id, $idsArray, true);
        $checkedStatus = '';
        if ($isInArray) $checkedStatus = 'checked';
        switch ($type) {
            case 'sources':
                $anchor = $pref->getType();
                $anchor = '<i class="fab fa-' . $type . '-square"></i>';
                $html .= '  <div class="form-row search-item">	
                                <div class="col">	
                                    <!-- Handle -->	
                                    <div class="md-form">	
                                        <div class="row ">	
                                            <div class="col-8 align-self-center">	
                                                <label class="form-text text-muted text-left" for="preferences">	
                                                    <a class="card-link text-primary" target="twitter" href="' . $pref->getUrl() . '">' . $anchor . ' @' . $pref->getReference() . '	
                                                    </a>	
                                                </label>	
                                                <!-- Name -->	
                                                <label class="form-text text-dark text-left" for="preferences">' . $pref->getName() . '</label>	
                                            </div>	
                                            <div class="col-4 align-self-center">	
                                                <input type="checkbox" name="' . $id . '" value="' . $id . '" ' . $checkedStatus . ' data-toggle="toggle" data-style="ios">	
                                            </div>	
                                        </div>	
                                    </div>	
                                </div>                               	
                            </div>';
                break;
            case 'topics':
                $html .= '  <div class="form-row search-item">	
                                <div class="col">	
                                    <!-- Handle -->	
                                    <div class="md-form">	
                                        <div class="row ">	
                                            <div class="col-8 align-self-center">	
                                                <label class="form-text text-dark text-left" for="preferences">' . $pref->description . '</label>	
                                                <label class="form-text text-muted text-left" for="preferences"><strong>#' . $pref->name . '</strong></label>	
                                            </div>	
                                            <div class="col-4 align-self-center">	
                                                <input type="checkbox" name="' . $id . '" value="' . $id . '" ' . $checkedStatus . ' data-toggle="toggle" data-style="ios">	
                                            </div>	
                                        </div>	
                                    </div>	
                                </div>                               	
                            </div>';
                break;
        }
    }

    return $html;
}
