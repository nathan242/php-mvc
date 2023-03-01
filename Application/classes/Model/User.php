<?php

namespace Application\Model;

use Framework\Database\Interfaces\DatabaseInterface;
use Framework\Database\SqlBuilder;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Mvc\Session;

/**
 * User table model
 *
 * @package Application\Model
 * @property int $id
 */
class User extends Model
{
    /** @var string $table */
    protected $table = 'users';

    /** @var Session $session */
    protected $session;

    /**
     * User constructor
     *
     * @param DatabaseInterface $db
     * @param SqlBuilder $sqlBuilder
     * @param ModelCollection $modelCollection
     * @param Session $session
     */
    public function __construct(DatabaseInterface $db, SqlBuilder $sqlBuilder, ModelCollection $modelCollection, Session $session)
    {
        $this->session = $session;
        parent::__construct($db, $sqlBuilder, $modelCollection);
    }

    /**
     * Check if a valid user is logged in.
     *
     * @return bool
     */
    public function checkLoggedIn(): bool
    {
        if (
            !isset($this->session->userId)
            || !$this->retrieveWhere(['id' => $this->session->userId, 'enabled' => 1])
        ) {
            return false;
        }

        return true;
    }

    /**
     * Attempt to log in. If successful the user data will be set in the session.
     *
     * @param string $username Username
     * @param string $password Password
     * @return bool
     */
    public function login($username, $password): bool
    {
        $hash = hash('sha256', $password);
        if (!$this->retrieveWhere(['username' => $username, 'password' => $hash, 'enabled' => 1])) {
            return false;
        } else {
            $this->session->userId = $this->id;
            $this->session->loginuser = $username;
            return true;
        }
    }
}
