<?php
    namespace commands;

    class test_commands extends base_command {
        public function test() {
            echo "Hello World!!!\n";
            return 0;
        }

        public function dump_config($name = null) {
            echo print_r($this->config->get($name), 1)."\n";
            return 0;
        }
    }
