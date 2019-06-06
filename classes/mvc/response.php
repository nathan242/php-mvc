<?php
    namespace mvc;
    
    use mvc\interfaces\response_content;

    class response {
        private static $instance = null;

        public $code = 200;
        public $content = '';
        public $headers = [];

	public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
	    }

	    return self::$instance;
	}

        public function send() {
            http_response_code($this->code);
            foreach ($this->headers as $header => $value) {
                header($header.': '.$value);
            }

            if (is_object($this->content) && $this->content instanceof response_content) {
                exit($this->content->output_content());
            }
            exit($this->content);
        }

        public static function set($code = 200, $content = '', $headers = []) {
            $instance = self::get_instance();
            $instance->code = $code;
            $instance->content = $content;
            $instance->headers = $headers;

            return $instance;
        }
    }
