<?php
    namespace commands;

    abstract class base_command {
        protected $config;

        public function set_config($config) {
            $this->config = $config;
        }
    }
