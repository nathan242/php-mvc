<?php
    namespace mvc;

    use RuntimeException;

    class config {
        protected $config_path;
        public $local = [];

        public function __construct($config_path, $local = []) {
            if (!is_dir($config_path)) {
                throw new RuntimeException("Configuration directory not found ($config_path)");
            }

            $this->config_path = $config_path;
            $this->local = $local;
        }

        public function get($name) {
            $config = [];

            $file = "$this->config_path/$name.php";

            if (is_file($file)) {
                $local = $this->local;
                $config = require $file;
            }

            return $config;
        }
    }

