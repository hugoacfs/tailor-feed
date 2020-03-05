<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<script>
    $(document).ready(function() {
        $("#search-area-admin").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $(".table tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>
<div class="col-xs-12 col-sm-12 col-lg-12">
    <nav>
        <ol class="breadcrumb text-light bg-dark">
            <li class="breadcrumb-item">
                <a href="admin.php">Admin</a>
            </li>
            <li class="breadcrumb-item active text-light">
                Settings
            </li>
        </ol>
    </nav>
    <div class="input-group mb-3">
        <div class="input-group-prepend">
            <span class="input-group-text text-light bg-dark" id="basic-addon1">Search</span>
        </div>
        <input id="search-area-admin" type="text" class="form-control text-light bg-dark" placeholder="Example: chiuni" aria-label="Search" aria-describedby="basic-addon1">
    </div>
    <div class="bg-dark p-3 rounded" style="max-height: 70vh; overflow: auto;">
        <form class="text-white">
            <div class="md-form">
                <div class="row ">
                    <div class="col-4 ">
                        <label class="form-text " for="preferences">Setting Name:</label>
                    </div>
                    <div class="col-4 ">
                        <label class="form-text " for="preferences"> Status: </label>
                    </div>
                    <div class="col-4 ">
                        <label class="form-text " for="preferences"> Last Run: </label>
                    </div>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a data-toggle="modal" onclick="updateSettings()" data-target="#modal" type="button" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Articles Recycle Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="articles_recycle_mode" id="articles_recycle_mode_on" value="on" <?php if ($CFG->articles_recycle_mode === 'on') echo 'checked'; ?>>
                            <label class="form-check-label" for="articles_recycle_mode_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="articles_recycle_mode" id="articles_recycle_mode_off" value="off" <?php if ($CFG->articles_recycle_mode != 'on') echo 'checked'; ?>>
                            <label class="form-check-label" for="articles_recycle_mode_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->articles_recycle_last_cron); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Users Recycle Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="users_recycle_mode" id="users_recycle_mode_on" value="on" <?php if ($CFG->users_recycle_mode === 'on') echo 'checked'; ?>>
                            <label class="form-check-label" for="users_recycle_mode_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="users_recycle_mode" id="users_recycle_mode_off" value="off" <?php if ($CFG->users_recycle_mode != 'on') echo 'checked'; ?>>
                            <label class="form-check-label" for="users_recycle_mode_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->users_recycle_last_cron); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Twitter Articles Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="twitter_update_articles" id="twitter_update_articles_on" value="on" <?php if ($CFG->sources['twitter']['update_articles'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="twitter_update_articles_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="twitter_update_articles" id="twitter_update_articles_off" value="off" <?php if ($CFG->sources['twitter']['update_articles'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="twitter_update_articles_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['twitter']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Twitter Sources Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="twitter_update_sources" id="twitter_update_sources_on" value="on" <?php if ($CFG->sources['twitter']['update_sources'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="twitter_update_sources_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="twitter_update_sources" id="twitter_update_sources_off" value="off" <?php if ($CFG->sources['twitter']['update_sources'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="twitter_update_sources_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['twitter']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Facebook Articles Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="facebook_update_articles" id="facebook_update_articles_on" value="on" <?php if ($CFG->sources['facebook']['update_articles'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="facebook_update_articles_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="facebook_update_articles" id="facebook_update_articles_off" value="off" <?php if ($CFG->sources['facebook']['update_articles'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="facebook_update_articles_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['facebook']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> Facebook Sources Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="facebook_update_sources" id="facebook_update_sources_on" value="on" <?php if ($CFG->sources['facebook']['update_sources'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="facebook_update_sources_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="facebook_update_sources" id="facebook_update_sources_off" value="off" <?php if ($CFG->sources['facebook']['update_sources'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="facebook_update_sources_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['facebook']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> RSS Articles Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rss_update_articles" id="rss_update_articles_on" value="on" <?php if ($CFG->sources['rss']['update_articles'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="rss_update_articles_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rss_update_articles" id="rss_update_articles_off" value="off" <?php if ($CFG->sources['rss']['update_articles'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="rss_update_articles_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['rss']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-4"><a href="#" class="text-primary"><i class="fas fa-cogs fa-lg"></i></a> RSS Sources Update Mode</div>
                <div class="col-4">
                    <div class="form-check">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rss_update_sources" id="rss_update_sources_on" value="on" <?php if ($CFG->sources['rss']['update_sources'] === 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="rss_update_sources_on">
                                Enabled
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="rss_update_sources" id="rss_update_sources_off" value="off" <?php if ($CFG->sources['rss']['update_sources'] != 'true') echo 'checked'; ?>>
                            <label class="form-check-label" for="rss_update_sources_off">
                                Disabled
                            </label>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <label class="form-check-label"><?php echo timeAgo($CFG->sources['rss']['last_cron']); ?></label>
                </div>
            </div>
            <hr class="bg-white">
            <div class="form-group row">
                <div class="col-5">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </div>
            </div>
        </form>

    </div>
    <hr>
</div>
<script>
    $('.update-setting .btn .btn-success').click(function() {
        $('form[name="modalForm"]').submit();
    });
</script>