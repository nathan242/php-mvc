<?php
    namespace mvc;

    class request {
        public $method;
        public $path;
        public $params;

        public function get() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->path = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
            $this->params = [
                'GET' => $_GET,
                'POST' => $_POST
            ];
        }

        public function param($name, $default, $type = null) {
            $return = $default;
            $type = null === $type ? ['GET', 'POST'] : (array)$type;

            foreach ($type as $param_type) {
                if (array_key_exists($name, $this->params[$param_type])) {
                    $return = $this->params[$param_type][$name];
                    break;
                }
            }

            return $return;
        }
    }

