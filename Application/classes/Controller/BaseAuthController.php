<?php

namespace Application\Controller;

use Framework\Mvc\Exceptions\ResponseException;

abstract class BaseAuthController extends BaseAppController
{
    public function init()
    {
        parent::init();

        if (!$this->user->check_logged_in()) {
            $this->session->destroy();

            throw new ResponseException($this->response->set(302, '', ['Location' => '/']));
        }
    }
}

