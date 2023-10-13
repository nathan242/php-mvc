<?php

namespace Application\Controller;

use Application\Model\User;
use Framework\Controller\BaseController;

/**
 * Base web controller
 *
 * @package Application\Controller
 */
abstract class BaseAppController extends BaseController
{
    /** @var User $user */
    protected $user;

    /**
     * Initialize controller then start session
     */
    public function init(): void
    {
        parent::init();
        $this->session->start();
    }

    /**
     * Set the user object
     *
     * @param $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }
}

