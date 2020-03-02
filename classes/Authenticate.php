<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}

class Authenticate
{
    protected $userName;
    protected $passWord;
    protected $userId;
    protected $givenName;
    protected $signedIn;
    protected $role;
    protected $error;

    function __construct(string $userName, string $passWord, string $givenName = 'not-set', bool $newUserMode = false)
    {
        global $DB;
        $this->userName = $userName;
        $user = $this->getUser();
        $exists = count($user) ?? false;
        if (!$newUserMode && $exists) {
            $this->passWord = $user['password'];
            // echo $passWord;
            // echo '<br>users password from input, hashed>'.password_hash($passWord, PASSWORD_BCRYPT);
            // echo '<br>users password from DB, hashed>'.$this->passWord;
            // echo 'is signed in>>>>' . $isSignedIn;
            $this->signedIn = password_verify($passWord, $this->passWord);
            $this->givenName = $user['givenname'];
            $this->role = $user['role'];
            $this->userId = $user['id'];
        } elseif ($newUserMode && !$exists) {
            $this->givenName = $givenName;
            $this->passWord = password_hash($passWord, PASSWORD_BCRYPT);
            $this->role = 'u';
            $this->signedIn = $this->buildUserProfile() ?? false;
            if (!$this->signedIn) {
                $this->error = array('code' => '300', 'description' => 'Cannot build user profile, please try again.');
            }
        } elseif ($newUserMode && $exists) {
            $this->error = array('code' => '100', 'description' => 'Cannot create user, already exists.');
        } elseif (!$newUserMode && !$exists) {
            $this->error = array('code' => '200', 'description' => 'Cannot find username.');
        }
        if ($this->signedIn) {
            $DB->updateLastLogin($this->userName);
        }
    }

    public function getSignedIn()
    {
        return $this->signedIn;
    }

    protected function buildUserProfile(): bool
    {
        global $DB;
        $success = $DB->insertUser($this->userName, $this->givenName, $this->passWord);
        if ($success) {
            $this->userId = $DB->PDOgetlastinsertid();
            $DB->updateLastLogin($this->userName);
            $this->displayWelcomeMessage();
        }
        return $success;
    }
    function getUser()
    {
        global $DB;
        $fetch = $DB->fetchUserByUsername($this->userName);
	$fetched = $fetch->fetch();
	if(is_array($fetched)){
	    return $fetched;
	}else{
	    return array();
	}
    }
    protected function displayWelcomeMessage()
    {
        $_SESSION['welcomemessage'] = true;
        $_SESSION['wmtimestamp'] = time();
    }
    function getUserName()
    {
        return $this->userName;
    }
    function getUserId()
    {
        return $this->userId;
    }
    function getGivenName()
    {
        return $this->givenName;
    }
    function getRole()
    {
        return $this->role;
    }
    function getError()
    {
        return $this->error;
    }
}
