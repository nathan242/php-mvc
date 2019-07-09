<?php
    namespace controller;

    use mvc\response;
    use mvc\view;
    use mvc\object_storage;

    class login {
        public function login() {
            $session = object_storage::get('session');

            if (!$session->has('user_id') && (!array_key_exists('username', $_POST) || !array_key_exists('password', $_POST))) {
                return response::set(200, view::set('login.php', ['app' => object_storage::get('config')->get('application')]));
            } elseif (!$session->has('user_id') && array_key_exists('username', $_POST) && array_key_exists('password', $_POST)) {
                if (false) {
                    header('Location: main');
                    exit();
                } else {
                    return response::set(200, view::set('login-fail.php', ['app' => object_storage::get('config')->get('application')]));
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

