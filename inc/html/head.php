<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <?php
    if (!defined('DESKTOP_VIEW')) echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    else echo '<meta name="viewport" content="width=1024">';
    ?>
    <link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
    <title><?php echo $title; ?></title>
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Fontawesome -->
    <link rel="stylesheet" href="css/all.min.css">
    <!-- ROBOTO Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <!-- TOGGLE CSS -->
    <link rel="stylesheet" href="vendor/bootstrap4-toggle-master/css/bootstrap4-toggle.css">
    <?php
    // https://github.hubspot.com/sortable/api/themes/
    if ($pageId === 'admin') {
        echo '<script src="vendor/sortable/js/sortable.min.js"></script>';
        echo '<link rel="stylesheet" href="vendor/sortable/css/sortable-theme-bootstrap.css" />';
    }
    if ($CFG->g_analytics_mode === 'on') require_once($CFG->dirroot . '/inc/html/analytics.php');
    ?>
</head>