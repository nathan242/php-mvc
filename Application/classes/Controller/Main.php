<?php

namespace Application\Controller;

use Framework\Mvc\Exceptions\ResponseException;
use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Main page
 *
 * @package Application\Controller
 */
class Main extends BaseAuthController
{
    /**
     * Initialize controller
     *
     * @throws ResponseException
     */
    public function init()
    {
        parent::init();
        $this->view->setView('template.phtml', ['topbar' => true, 'loginuser' => $this->session->loginuser, 'pagepath' => [['MAIN', $this->request->path]]]);
    }

    /**
     * Show main page
     *
     * @return ResponseInterface
     */
    public function main(): ResponseInterface
    {
        return $this->response->set(200, $this->view->get('main.phtml'));
    }
}
