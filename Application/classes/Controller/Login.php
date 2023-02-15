<?php

namespace Application\Controller;

class Login extends BaseAppController
{
    protected $view;
    protected $app_config;

    public function init()
    {
        parent::init();
        $this->app_config = $this->config->get('application');
        $this->view->setView('template.phtml', ['pagetitle' => "{$this->app_config['name']} Login"]);
    }

    public function login()
    {
        if (!isset($this->session->user_id) && !$this->request->hasParam('username', 'POST') && !$this->request->hasParam('password', 'POST')) {
            return $this->response->set(200, $this->view->get('login.phtml', ['app_name' => $this->app_config['name']]));
        } elseif (!isset($this->session->user_id) && $this->request->hasParam('username', 'POST') && $this->request->hasParam('password', 'POST')) {
            if ($this->user->login($this->request->param('username', null, 'POST'), $this->request->param('password', null, 'POST'))) {
                return $this->response->set(302, '', ['Location' => 'main']);
            } else {
                return $this->response->set(200, $this->view->get('login-fail.phtml'));
            }
        } else {
            return $this->response->set(302, '', ['Location' => 'main']);
        }
    }

    public function logout()
    {
        $this->session->destroy();
        return $this->response->set(302, '', ['Location' => 'main']);
    }
}
