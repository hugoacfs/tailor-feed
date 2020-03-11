<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
?>
<!-- The Modal -->
<div class="modal" id="pagesModal" tabindex="-1" role="dialog" aria-labelledby="pagesModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pagesModalCenteredLabel">Following</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Search</span>
                            </div>
                            <input id="search-area-pages" type="text" class="form-control search-me" placeholder="Example: chiuni" aria-label="Search">
                        </div>
                    </div>
                    <div class="col-12">
                        <form id="pages-modal-form" class="text-center" style="color: #757575;" action="" method="POST">
                            <div class="spinner-border text-primary" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                        </form>
                        <!-- Form -->
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button form="pages-modal-form" name="submitpages" class="btn btn-primary" id="submitpages" type="submitpages">Save changes</button>
            </div>
        </div>
    </div>
</div>