<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use mvc\object_storage;

    class login extends base_controller {
        public function login() {
            if (!$this->session->has('user_id') && !$this->request->has_param('username', 'POST') || !$this->request->has_param('password', 'POST')) {
                return response::set(200, view::set('login.php', ['app' => $this->config->get('application')]));
            } elseif (!$this->session->has('user_id') && $this->request->has_param('username', 'POST')  && $this->request->has_param('password', 'POST')) {
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

