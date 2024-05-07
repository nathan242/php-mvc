<?php

namespace Application\Controller;

use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Session data viewer
 *
 * @package Application\Controller
 */
class Session extends BaseAuthController
{
    /**
     * Initialize class
     *
     * @throws ResponseException
     */
    public function init(): void
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Session', $this->request->path]]]);
    }

    /**
     * Show session data
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        return $this->response->set(200, $this->view->get('debug.phtml', ['data' => print_r($_SESSION, true)]));
    }
}

