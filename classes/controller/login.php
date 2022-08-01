<?php
    namespace controller;

    use mvc\response;
    use mvc\view;

    class login extends base_controller {
        protected $view;
        protected $app_config;

        public function init() {
            $this->app_config = $this->config->get('application');
            $this->view->set_view('template.php', ['pagetitle' => "{$this->app_config['name']} Login"]);
            parent::init();
        }

        public function login() {
            if (!isset($this->session->user_id) && !$this->request->has_param('username', 'POST') && !$this->request->has_param('password', 'POST')) {
                return $this->response->set(200, $this->view->get('login.php', ['app_name' => $this->app_config['name']]));
            } elseif (!isset($this->session->user_id) && $this->request->has_param('username', 'POST')  && $this->request->has_param('password', 'POST')) {
                if ($this->user->login($this->request->param('username', null, 'POST'), $this->request->param('password', null, 'POST'))) {
                    return $this->response->set(302, '', ['Location' => 'main']);
                } else {
                    return $this->response->set(200, $this->view->get('login-fail.php'));
                }
            } else {
                return $this->response->set(302, '', ['Location' => 'main']);
            }
        }

        public function logout() {
            $this->session->destroy();
            return $this->response->set(302, '', ['Location' => 'main']);
        }
    }
