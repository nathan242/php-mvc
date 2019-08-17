<?php
    namespace controller;

    abstract class base_controller {
        protected $request;
        protected $session;
        protected $config;

        public function set_request($request) {
            $this->request = $request;
        }

        public function set_session($session) {
            $this->session = $session;
        }

        public function set_config($config) {
            $this->config = $config;
        }
    }
