<?php
    namespace framework\mvc;
    
    use framework\mvc\interfaces\response_content;
    use framework\mvc\interfaces\response_interface;

    class response implements response_interface {
        public $code = 200;
        public $content = '';
        public $headers = [];
        public $cookies = [];

        public function send() {
            foreach ($this->cookies as $name => $cookie) {
                setcookie($name, $cookie['value'], $cookie['expires'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['http_only']);
            }

            http_response_code($this->code);
            foreach ($this->headers as $header => $value) {
                header($header.': '.$value);
            }

            if (is_object($this->content) && $this->content instanceof response_content) {
                echo $this->content->output_content();
                return;
            }

            echo $this->content;
        }

        public function set($code = 200, $content = '', $headers = []) {
            $this->code = $code;
            $this->content = $content;
            $this->add_headers($headers);

            return $this;
        }

        public function add_headers($headers) {
            $this->headers = array_merge($this->headers, $headers);
        }

        public function add_cookie($name, $value = '', $expires = 0, $path = '', $domain = '', $secure = false, $http_only = false) {
            $this->cookies[$name] = [
                'value' => $value,
                'expires' => $expires,
                'path' => $path,
                'domain' => $domain,
                'secure' => $secure,
                'http_only' => $http_only
            ];
        }
    }
