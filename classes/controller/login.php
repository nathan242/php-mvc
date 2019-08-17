<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use mvc\object_storage;

    class login {
        private $request;
        private $session;
        private $config;

        public function __construct($request, $session, $config) {
            $this->request = $request;
            $this->session = $session;
            $this->config = $config;
        }

        public function login() {
            if (!$this->session->has('user_id') && (!array_key_exists('username', $_POST) || !array_key_exists('password', $_POST))) {
                return response::set(200, view::set('login.php', ['app' => $this->config->get('application')]));
            } elseif (!$this->session->has('user_id') && array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
                if (false) {
                    header('Location: main');
                    exit();
                } else {
                    return response::set(200, view::set('login-fail.php', ['app' => $this->config->get('application')]));
                }
            } else {
                header('Location: main');
                exit();
            }
        }

        public function logout() {
            session_destroy();

            header('Location: /');
            exit();
        }
    }

