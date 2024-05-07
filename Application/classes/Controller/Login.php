<?php

namespace Application\Controller;

use Framework\Mvc\Interfaces\ResponseInterface;

/**
 * Login page
 *
 * @package Application\Controller
 */
class Login extends BaseAppController
{
    /** @var array<string, mixed> $appConfig */
    protected $appConfig;

    /**
     * Initialize controller
     */
    public function init(): void
    {
        parent::init();
        $this->appConfig = $this->config->get('application');
        $this->view->setView('template.phtml', ['pagetitle' => "{$this->appConfig['name']} Login"]);
    }

    /**
     * Handle login
     *
     * @return ResponseInterface
     */
    public function login(): ResponseInterface
    {
        if ($this->user->checkLoggedIn()) {
            return $this->response->set(302, '', ['Location' => 'main']);
        }

        if ($this->request->hasParam('username', 'POST') && $this->request->hasParam('password', 'POST')) {
            if ($this->user->login($this->request->param('username', null, 'POST'), $this->request->param('password', null, 'POST'))) {
                return $this->response->set(302, '', ['Location' => 'main']);
            } else {
                return $this->response->set(200, $this->view->get('login-fail.phtml'));
            }
        }

        return $this->response->set(200, $this->view->get('login.phtml', ['app_name' => $this->appConfig['name']]));
    }

    /**
     * Handle logout
     *
     * @return ResponseInterface
     */
    public function logout(): ResponseInterface
    {
        $this->session->destroy();
        return $this->response->set(302, '', ['Location' => 'main']);
    }
}
