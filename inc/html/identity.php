<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<div style="display: none;">
    <i id="current-username"><?php echo $_SESSION['userName']; ?></i>
    <i id="current-userid"><?php echo $_SESSION['userId']; ?></i>
    <i id="current-safelock"><?php echo false; ?></i>
    <i id="current-page">1</i>
</div>