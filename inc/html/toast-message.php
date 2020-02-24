<?php
if (!defined('CONFIG_PROTECTION')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
if (isset($_SESSION['givenname']) && isset($_SESSION['welcomemessage'])) {
    // unset($_SESSION['welcomemessage']);
    $name = $_SESSION['givenname'];
    echo '  <div class="toast" data-autohide="false" style="position: absolute; top: 0; right: 0; min-width: 210px;">
            <div class="toast-header">
              <strong class="mr-auto text-primary">Welcome Message</strong>
              <small class="text-muted"> ' . timeAgo($_SESSION['wmtimestamp']) . '</small>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body">
              Welcome ' . $name . '!
              <br>
              Click on `@` and `#` to change what you see.
            </div>
          </div>';
}
?>
<script>
    $(document).ready(function() {
        $('.toast').toast('show');
    });
</script>
<?php
unset($_SESSION['welcomemessage']);
?>