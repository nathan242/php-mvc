<?php

namespace Application\Controller;

use Framework\Controller\BaseController;

abstract class BaseAppController extends BaseController
{
    protected $user;

    public function init()
    {
        parent::init();
        $this->session->start();
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}

