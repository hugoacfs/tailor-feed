<?php
session_start();
if (!isset($_SESSION['signedIn'])) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
$forbid = $_POST['safelock'] ?? 'true';
if ($forbid === 'true') {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
} else define('CONFIG_PROTECTION', false);
require_once __DIR__ . '/../../config.php';
if (isset($_POST['username'])) $user = new User($_POST['username']);
elseif (isset($_SESSION['currentUser'])) $user = $_SESSION['currentUser'];
if (!isset($user)) exit;
$user_pref_list = $user->getPreferences('topic');
$active = true;
$allTopics = Article::getAllTopics();
echo '	
<div class="form-row">	
    <div class="col">	
        <!-- Handle -->	
        <div class="md-form">	
            <div class="row ">	
                <div class="col-8 align-self-center">	
                    <label class="form-text text-muted text-left" for="preferences">Topic Name:</label>	
                </div>	
                <div class="col-4 align-self-center">	
                    <label class="form-text text-muted text-left" for="preferences"> Subscribed: </label>	
                </div>	
            </div>	
        </div>	
    </div>                               	
</div>	
<hr class="mb-0" />';
foreach ($allTopics as $topic) {
    $topicId = $topic->dbId;
    $arrayOfIds = getSubscribedIds($user_pref_list);
    $isInArray = in_array($topicId, $arrayOfIds, true);
    $checkedStatus = '';
    if ($isInArray) {
        $checkedStatus = 'checked';
    }
    echo '	
        <div class="form-row search-item">	
            <div class="col">	
                <!-- Handle -->	
                <div class="md-form">	
                    <div class="row ">	
                        <div class="col-8 align-self-center">	
                            <label class="form-text text-dark text-left" for="preferences">' . $topic->description . '</label>	
                            <label class="form-text text-muted text-left" for="preferences"><strong>#' . $topic->name . '</strong></label>	
                        </div>	
                        <div class="col-4 align-self-center">	
                            <input type="checkbox" name="' . $topicId . '" value="' . $topicId . '" ' . $checkedStatus . ' data-toggle="toggle" data-style="ios">	
                        </div>	
                    </div>	
                </div>	
            </div>                               	
        </div>';
}
