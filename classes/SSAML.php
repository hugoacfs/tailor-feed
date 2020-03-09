<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}
ini_set('display_errors', 1);
class SSAML extends Authenticate
{
    function __construct()
    {
        global $DB;
        require_once('/var/simplesamlphp/lib/_autoload.php');
        $auth = new \SimpleSAML\Auth\Simple('default-sp');
        if (!$auth->isAuthenticated()) {
            $auth->requireAuth();
        }
        $SSAMLuser = $auth->getAttributes();
        $username = strtolower($SSAMLuser['SamAccountName'][0]);
        $this->userName = $username;
        $givenname = ucfirst(strtolower($SSAMLuser['givenName'][0]));
        $this->givenName = $givenname;
        $DBuser = $this->getUser();
        $exists = false;
        if ($DBuser) $exists = true;
        if (!$exists) {
            $this->passWord = null; //needs null password to attempt building user profile
            $this->signedIn = $this->buildUserProfile() ?? false;
        }
        if ($exists) {
            $this->role = $DBuser['role'];
            $this->signedIn = true;
        }
        $this->userId = $DBuser['id'] ?? null;
        $session = \SimpleSAML\Session::getSessionFromRequest();
        $session->cleanup();
        $_SESSION['logout_url'] = $auth->getLogoutURL('/logout.php');
        if ($this->signedIn) {
            $DB->updateLastLogin($this->userName);
        }
    }
    protected function buildUserProfile(): bool
    {
        global $DB;
        $success = $DB->insertUser($this->userName, $this->givenName, 'none');
        if ($success) {
            $this->userId = $DB->PDOgetlastinsertid();
            $DB->updateLastLogin($this->userName);
            $this->displayWelcomeMessage();
        }
        return $success;
    }
}
