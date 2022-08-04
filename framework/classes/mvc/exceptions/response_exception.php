<?php
    namespace framework\mvc\exceptions;

    use Exception;

    class response_exception extends Exception {
        protected $response;

        public function __construct($response) {
            $this->response = $response;
            parent::__construct('Unhandled response exception');
        }

        public function get_response() {
            return $this->response;
        }
    }
