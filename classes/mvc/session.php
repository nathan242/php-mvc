<?php
    namespace mvc;

    class session {
        public function start() {
            session_start();
        }

        public function __isset($name) {
            return array_key_exists($name, $_SESSION);
        }

        public function __get($name) {
            return $_SESSION[$name] ?? null;
        }

        public function __set($name, $value) {
            $_SESSION[$name] = $value;
        }

        public function destroy() {
            session_destroy();
        }
    }

