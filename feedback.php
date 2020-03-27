<?php
// disabled
header('Location: index.php');
exit;
define('CONFIG_PROTECTION', false);
$title = 'Feedback';
$pageId = 'feedback';
require_once(__DIR__ . '/config.php');
session_start();
require_once($CFG->dirroot . '/inc/php/authenticate.php');
require_once($CFG->dirroot . '/inc/html/head.php');
require_once($CFG->dirroot . '/inc/html/nav.php');
if (!isLoggedIn()) {
    redirectGuestToLogin();
}
?>
<style>
    html,
    body {
        min-height: 100%;
        min-width: 100%;
        width: 100%;
        height: 100%;
        margin: 0;
        padding: 0
    }

    .row-container {
        display: flex;
        width: 100%;
        height: 100%;
        flex-direction: column;
        background-color: blue;
        overflow: hidden;
    }

    .row {
        width: 100%;
    }
</style>
<div class="row-container">
    <iframe src="https://forms.office.com/Pages/ResponsePage.aspx?id=EShiMk38hEeAqP2ZHu3Zh304xbVaGmBGtSnfHP9OAsdUMFlUOTI3WTYwR0I0MjdESzhBQUdaQVJGQy4u&embed=true" frameborder="0" marginwidth="0" marginheight="0" style="border: none; min-width:100%; min-height:100vh" allowfullscreen webkitallowfullscreen mozallowfullscreen msallowfullscreen> </iframe>
</div>
</body>

</html>