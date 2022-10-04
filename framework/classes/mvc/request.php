<?php
    namespace framework\mvc;

    use framework\mvc\interfaces\request_interface;

    class request implements request_interface {
        public $method;
        public $path;
        public $params;
        public $body;

        public function get() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->path = preg_replace('/\?(.+)?/', '', $_SERVER['REQUEST_URI']);
            $this->params = [
                'GET' => $_GET,
                'POST' => $_POST,
                'FILES' => $_FILES
            ];
            $this->body = file_get_contents('php://input');
        }

        public function param($name, $default = null, $type = null) {
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

        public function files() {
            $files = [];

            foreach ($this->params['FILES'] as $file) {
                $files[] = $file['name'];
            }

            return $files;
        }

        public function store_file($name, $dest) {
            $file_data = null;

            foreach ($this->params['FILES'] as $file) {
                if ($name === null || $file['name'] === $name) {
                    $file_data = $file;

                    break;
                }
            }

            if ($file_data === null) { return false; }

            return move_uploaded_file($file_data['tmp_name'], $dest);
        }
    }

