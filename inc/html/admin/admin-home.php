<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<div class="col">
    <div class="table-responsive" style="max-height: 80vh;">
        <div class="jumbotron">
            <h2>
                Hello, <?php echo $_SESSION['givenname']; ?>!
            </h2>
            <p>
                This is the admin site for UOC News.
            </p>
            <p>
                <a class="btn btn-primary btn-large" data-toggle="collapse" href="#adminactivity" role="button" aria-expanded="false" aria-controls="adminactivity">See latest activity</a>
            </p>
            <div class="collapse" id="adminactivity">
                <div class="card card-body bg-dark text-white">
                    <?php
                    $fetch = $DB->fetchAdminLog();
                    foreach ($fetch as $row) {
                        $log = json_decode($row['description']);
                        $action = $log->action;
                        $sourceId = $log->sourceId;
                        echo '
                        <h5 class="card-subtitle mb-2 text-muted">
                            <a href="#" target="uni_news" class=" card-link">
                                Updating Source # ' . $sourceId . '  
                                <i class="fas fa-cogs"></i>
                            </a>
                        </h5>
                        <p>
                            <small class="text-muted">
                                <i class="glyphicon glyphicon-time"></i>
                                <i class="far fa-clock"> </i> ' . timeAgo($row['creationdate']) . '
                            </small>
                        </p>
                        <p class="card-text">' . $action . ' </p><hr>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>