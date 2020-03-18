<?php
if (!defined('CONFIG_PROTECTION')) {
  header('HTTP/1.0 403 Forbidden', true, 403);
  http_response_code(403);
  exit;
}
if (isset($_SESSION['givenName']) && isset($_COOKIE['welcomemessage'])) {
  $name = $_SESSION['givenName'];
  echo '  <div id="welcome-toast" class="toast" data-autohide="false" style="position: absolute; top: 58px; right: 0; min-width: 210px;">
            <div class="toast-header">
              <img src="img/favicon.ico" class="rounded mr-2" style="max-width: 25px;" alt="icon">
              <strong class="mr-auto text-primary">Welcome to News</strong>
              <small class="text-muted"> ' . timeAgo($_COOKIE['wmtimestamp']) . '</small>
              <button type="button" class="ml-2 mb-1 close" data-dismiss="toast">&times;</button>
            </div>
            <div class="toast-body">
              Hello ' . $name . ', welcome to the news site!
              <br>
              Click on \'@\' and \'#\' to follow accounts and topics.
            </div>
          </div>';
}