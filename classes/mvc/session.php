<?php
    namespace mvc;

    class session {
        private $name;

        public function __construct($name) {
            session_start();

            $this->name = $name;

            if (!array_key_exists($name, $_SESSION) || !is_array($_SESSION[$name])) {
                $_SESSION[$name] = [];
            }
        }

        public function get($key) {
            return array_key_exists($key, $_SESSION[$this->name]) ? $_SESSION[$this->name] : false;
        }

        public function set($key, $value) {
            $_SESSION[$this->name][$key] = $value;
        }

        public function has($key) {
            return array_key_exists($key, $_SESSION[$this->name]);
        }
    }

