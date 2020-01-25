<?php
    namespace mvc\interfaces;

    interface container_interface {
        public function create($name);
        public function set($name, $object);
        public function get($name);
    }