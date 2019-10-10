<?php
    namespace mvc;
    
    use mvc\interfaces\response_content;

    class response {
        public $code = 200;
        public $content = '';
        public $headers = [];

        public function send() {
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

        public static function set($code = 200, $content = '', $headers = []) {
            $instance = new self();
            $instance->code = $code;
            $instance->content = $content;
            $instance->headers = $headers;

            return $instance;
        }
    }
