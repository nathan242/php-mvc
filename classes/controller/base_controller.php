<?php
    namespace controller;

    abstract class base_controller {
        protected $request;
        protected $response;
        protected $session;
        protected $config;
        protected $user;
        protected $view;

        public function set_request($request) {
            $this->request = $request;
        }

        public function set_response($response) {
            $this->response = $response;
        }

        public function set_session($session) {
            $this->session = $session;
        }

        public function set_config($config) {
            $this->config = $config;
        }

        public function set_user($user) {
            $this->user = $user;
        }

        public function set_view($view) {
            $this->view = $view;
        }

        public function init() {
            if (method_exists($this, 'check_logged_in')) {
                $this->check_logged_in();
            }
        }
    }
