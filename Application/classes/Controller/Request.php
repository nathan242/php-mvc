<?php

namespace Application\Controller;

use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Request object viewer
 *
 * @package Application\Controller
 */
class Request extends BaseAuthController
{
    /**
     * Initialize class
     *
     * @throws ResponseException
     */
    public function init(): void
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', '/main'], ['Request', $this->request->path]]]);
    }

    /**
     * Show request object
     *
     * @return ResponseInterface
     */
    public function index(): ResponseInterface
    {
        return $this->response->set(200, $this->view->get('debug.phtml', ['data' => print_r($this->request, true)]));
    }
}

