<?php

namespace Application\Controller;

use Framework\Mvc\Exceptions\ResponseException;

/**
 * Base controller for pages that require login
 *
 * @package Application\Controller
 */
abstract class BaseAuthController extends BaseAppController
{
    /**
     * Initialize controller and check that user is logged in
     *
     * @throws ResponseException
     */
    public function init()
    {
        parent::init();

        if (!$this->user->checkLoggedIn()) {
            $this->session->destroy();

            throw new ResponseException($this->response->set(302, '', ['Location' => '/']));
        }
    }
}

