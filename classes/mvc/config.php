<?php
    namespace mvc;

    use RuntimeException;

    class config {
        protected $config_path;

        public function __construct($config_path) {
            if (!is_dir($config_path)) {
                throw new RuntimeException("Configuration directory not found ($config_path)");
            }

            $this->config_path = $config_path;
        }

        public function get($name) {
            $config = [];

            $file = "$this->config_path/$name.php";

            if (is_file($file)) {
                $config = require $file;
            }

            return $config;
        }
    }

