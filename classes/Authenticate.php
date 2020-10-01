<?php
if (!defined('CLASS_LOADER')) {
    header('HTTP/1.0 403 Forbidden', true, 403);
    http_response_code(403);
    exit;
}

class Authenticate
{
    /**
     * The user's username
     * @var string
     */
    protected $userName;
    /**
     * The user's password as in the DB
     * @var string
     */
    protected $passWord;
    /**
     * The user id as in the DB
     * @var int
     */
    protected $userId;
    /**
     * The user's first name
     * @var string
     */
    protected $givenName;
    /**
     * User's signed in status - upon password verification
     * @var bool
     */
    protected $signedIn;
    /**
     * The user role as in DB, 'a' is admin 'u' is user
     * @var string
     */
    protected $role;
    /**
     * An error recorded by the constructor
     * @var array
     */
    protected $error;

    /**
     * Builds the user authentication vars, used in authenticate.php to build the session
     * @param string $userName user inputed username
     * @param string $passWord user inputed password
     * @param string $givenName user's first (can be inputed if registring a new user, if not is ignored)
     * @param bool $newUserMode if set to true, it will attempt to build a new user in the db.
     */
    function __construct(string $userName, string $passWord, string $givenName = 'not-set', bool $newUserMode = false)
    {
        global $DB;
        $this->userName = $userName;
        $user = $this->getUser();
        $exists = false;
        if ($user) $exists = true;
        if (!$newUserMode && $exists) {
            $this->passWord = $user['password'];
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
                throw new Exception('Sorry we cannot process your request at this time.');
            }
        } elseif ($newUserMode && $exists) {
            $this->error = array('code' => '100', 'description' => 'Cannot create user, already exists.');
            throw new Exception($this->error['description']);
        } elseif (!$newUserMode && !$exists) {
            $this->error = array('code' => '200', 'description' => 'Cannot find username.');
            throw new Exception('User or password do not match our record.');
        }
        if ($this->signedIn) {
            $DB->updateLastLogin($this->userName);
        }
    }

    public function getSignedIn()
    {
        return $this->signedIn;
    }

    /**
     * Builds the user db entry, and updates the last login of the user, triggers displayWelcomeMessage
     * @return bool if successful, returns true, else false
     */
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

    /**
     * Gets the user from the DB by username
     * @return array returns an array of the user information
     */
    function getUser(): array
    {
        global $DB;
        $fetch = $DB->fetchUserByUsername($this->userName);
        if ($fetch) return $fetch[0];
        return [];
    }

    /**
     * Creates a toast notification for new users
     */
    protected function displayWelcomeMessage(): void
    {
        $userId = $this->userId ?? 0;
        if ($userId == 0) return; //abort if user id not valid
        $toastName = 'welcomemessage';
        $header = 'Welcome to News';
        $body = 'Hello ' . $this->givenName . ', welcome to the news site!
                <br>
                Click on \'@\' and \'#\' to follow accounts and topics.';
        makeSomeToast($userId, $body, $toastName, $header); //no need for timestamp as its created now
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
