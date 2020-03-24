<?php
session_start();
$forbid = $_POST['safelock'] ?? 'true';
if (!isset($_SESSION['signedIn']) || !isset($_SESSION['userName']) || $forbid === 'true' || !isset($_POST['type'])) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    exit;
}
define('CONFIG_PROTECTION', false);
require_once(__DIR__ . '/../../config.php');
$html = loadPreferences($_POST['type'], $_SESSION['userName']);
echo $html;