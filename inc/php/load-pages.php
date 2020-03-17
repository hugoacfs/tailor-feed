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
if (isset($_POST['username'])) {
    $user = new User($_POST['username']);
} elseif (isset($_SESSION['currentUser'])) $user = $_SESSION['currentUser'];
if (!isset($user)) die();
$user_pref_list = $user->getPreferences('source');
$active = true;
$allSources = Source::getAllSources($active);
echo '
<div class="form-row">
    <div class="col">
        <!-- Handle -->
        <div class="md-form">
            <div class="row ">
                <div class="col-8 align-self-center">
                    <label class="form-text text-muted text-left" for="preferences">Account information:</label>
                </div>
                <div class="col-4 align-self-center">
                    <label class="form-text text-muted text-left" for="preferences"> Subscribed: </label>
                </div>
            </div>
        </div>
    </div>                               
</div>
<hr class="mb-0" />';
foreach ($allSources as $source) {
    $sourceId = $source->getDbId();
    $arrayOfIds = getSubscribedIds($user_pref_list);
    $type = $source->getType();
    $type = '<i class="fab fa-' . $type . '-square"></i>';
    $isInArray = in_array($sourceId, $arrayOfIds, true);
    $checkedStatus = '';
    if ($isInArray) $checkedStatus = 'checked';
    echo '
        <div class="form-row search-item">
            <div class="col">
                <!-- Handle -->
                <div class="md-form">
                    <div class="row ">
                        <div class="col-8 align-self-center">
                            <label class="form-text text-muted text-left" for="preferences">
                                <a class="card-link text-primary" target="twitter" href="'.$source->getUrl().'">' . $type . ' @' . $source->getReference() . '
                                </a>
                            </label>
                            <!-- Name -->
                            <label class="form-text text-dark text-left" for="preferences">' . $source->getName() . '</label>
                        </div>
                        <div class="col-4 align-self-center">
                            <input type="checkbox" name="' . $sourceId . '" value="' . $sourceId . '" ' . $checkedStatus . ' data-toggle="toggle" data-style="ios">
                        </div>
                    </div>
                </div>
            </div>                               
        </div>';
}
