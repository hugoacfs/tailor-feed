<?php
if (!defined('CONFIG_PROTECTION')) {
  header('HTTP/1.0 403 Forbidden', true, 403);
  http_response_code(403);
  exit;
}
echo '<div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px; z-index: 1000;">';
echo '<div style="position: fixed; top: 58px; right: 0;">';
$userId = $_SESSION['userId'] ?? null;
$loaf = $_COOKIE[$userId] ?? null;
if ($loaf) {
  $bread = unserialize($loaf) ?? [];
  $serving = serveToast($bread);
  // echo '<pre>';
  // print_r($bread);
  // echo '</pre>';
  echo $serving;
}
echo '</div>';
echo '</div>';