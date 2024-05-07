<?php

namespace Application\Auth;

use Application\Model\User as UserModel;
use Framework\Mvc\Session;

/**
 * User authentication
 *
 * @package Application\Auth
 */
class User
{
    /** @var UserModel $user */
    protected $user;

    /** @var Session $session */
    protected $session;

    /**
     * Class constructor
     *
     * @param UserModel $user
     * @param Session $session
     */
    public function __construct(UserModel $user, Session $session)
    {
        $this->user = $user;
        $this->session = $session;
    }

    /**
     * Get user model
     *
     * @return UserModel
     */
    public function getUserModel(): UserModel
    {
        return $this->user;
    }

    /**
     * Check if a valid user is logged in.
     *
     * @return bool
     */
    public function checkLoggedIn(): bool
    {
        return !(!isset($this->session->userId) || !$this->user->retrieveWhere(['id' => $this->session->userId, 'enabled' => 1]));
    }

    /**
     * Attempt to log in. If successful the user data will be set in the session.
     *
     * @param string $username Username
     * @param string $password Password
     * @return bool
     */
    public function login(string $username, string $password): bool
    {
        session_unset();
        session_regenerate_id();

        if (!$this->user->retrieveWhere(['username' => $username, 'enabled' => 1])) {
            return false;
        }

        if (password_verify($password, $this->user->password)) {
            $this->session->userId = $this->user->id;
            $this->session->loginuser = $this->user->username;
            $this->session->csrfToken = hash('sha256', session_id().time());

            return true;
        }

        return false;
    }
}

