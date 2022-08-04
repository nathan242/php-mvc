<?php
    namespace framework\mvc;

    use framework\mvc\interfaces\request_interface;

    class request implements request_interface {
        public $method;
        public $path;
        public $params;

        public function get() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->path = preg_replace('/\?(.+)?/', '', $_SERVER['REQUEST_URI']);
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

        public function has_param($name, $type = null) {
            return null !== $this->param($name, null, $type);
        }
    }

