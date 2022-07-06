<?php
    namespace command;

    class test_commands extends base_command {
        public function dump_config($args = []) {
            if (isset($args[1])) {
                echo print_r($this->config->get($args[1]), 1)."\n";
            }

            return 0;
        }
    }
