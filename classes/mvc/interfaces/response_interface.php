<?php
    namespace mvc\interfaces;

    interface response_interface {
        public function send();
        public function set($code, $content, $headers);
        public function add_headers($headers);
    }