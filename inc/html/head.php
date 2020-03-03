<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<html>

<head>
    <meta charset="UTF-8" />
    <?php
    if (!defined('DESKTOP_VIEW')) {
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    } else {
        echo '<meta name="viewport" content="width=1024">';
    }
    ?>
    <link rel="icon" href="img/favicon.ico" type="image/gif" sizes="16x16">
    <title><?php echo $title; ?></title>
    <!-- BOOTSTRAP CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- JQUERY -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <!-- POPPER JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
    <!-- BOOTSTRAP JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="css/style.css">
    <!-- Fontawesome -->
    <script src="https://kit.fontawesome.com/2a953cdc29.js" crossorigin="anonymous"></script>
    <!-- ROBOTO Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <!-- TOGGLE CSS -->
    <link rel="stylesheet" href="vendor/bootstrap4-toggle-master/css/bootstrap4-toggle.css">
    <!-- TOGGLE JS -->
    <script src="vendor/bootstrap4-toggle-master/js/bootstrap4-toggle.js"></script>
    <!-- LODASH MIN JS -->
    <script src="vendor/lodash/lodash.js"></script>
    <?php
    // https://github.hubspot.com/sortable/api/themes/
    if ($pageId === 'admin') {
        echo '<script src="vendor/sortable/js/sortable.min.js"></script>';
        echo '<link rel="stylesheet" href="vendor/sortable/css/sortable-theme-bootstrap.css" />';
    }
    if($CFG->g_analytics_mode === 'on'){
        include $CFG->dirroot . '/inc/html/analytics.php';
    }
    ?>

</head>
<script>
    $(document).ready(function() {
        $("body").tooltip({
            selector: '[data-toggle=tooltip]'
        });
    });
</script>