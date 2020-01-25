<?php
    namespace mvc\interfaces;

    interface request_interface {
        public function get();
        public function param($name, $default, $type);
        public function has_param($name, $type);
    }