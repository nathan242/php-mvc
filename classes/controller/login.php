<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use mvc\object_storage;

    class login extends base_controller {
        protected $view;
        protected $app_config;

        public function init() {
            $this->app_config = $this->config->get('application');
            $this->view = view::set('template.php', ['pagetitle' => "{$this->app_config['name']} Login"]);
            parent::init();
        }

        public function login() {
            if (!$this->session->has('user_id') && !$this->request->has_param('username', 'POST') && !$this->request->has_param('password', 'POST')) {
                return response::set(200, $this->view->get('login.php', ['app_name' => $this->app_config['name']]));
            } elseif (!$this->session->has('user_id') && $this->request->has_param('username', 'POST')  && $this->request->has_param('password', 'POST')) {
                if ($this->user->login($this->request->param('username', null, 'POST'), $this->request->param('password', null, 'POST'))) {
                    header('Location: main');
                    exit();
                } else {
                    return response::set(200, $this->view->get('login-fail.php'));
                }
            } else {
                header('Location: main');
                exit();
            }
        }

        public function logout() {
            $this->session->destroy();

            header('Location: /');
            exit();
        }
    }

