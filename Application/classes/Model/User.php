<?php

namespace Application\Model;

use Framework\Database\Interfaces\DatabaseInterface;
use Framework\Database\SqlBuilder;
use Framework\Model\Model;
use Framework\Model\ModelCollection;
use Framework\Mvc\Session;

class User extends Model
{
    protected $table = 'users';
    protected $session;

    public function __construct(DatabaseInterface $db, SqlBuilder $sql_builder, ModelCollection $model_collection, Session $session)
    {
        $this->session = $session;
        parent::__construct($db, $sql_builder, $model_collection);
    }

    /**
     * Check if a valid user is logged in.
     *
     * @return boolean
     */
    public function check_logged_in()
    {
        if (
            !isset($this->session->user_id)
            || !$this->retrieveWhere(['id' => $this->session->user_id, 'enabled' => 1])
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
     * @return boolean
     */
    public function login($username, $password)
    {
        $hash = hash('sha256', $password);
        if (!$this->retrieveWhere(['username' => $username, 'password' => $hash, 'enabled' => 1])) {
            return false;
        } else {
            $this->session->user_id = $this->id;
            $this->session->loginuser = $username;
            return true;
        }
    }
}
