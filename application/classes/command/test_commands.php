<?php
    namespace application\command;

    use framework\command\base_command;
    use application\model\test;

    class test_commands extends base_command {
        protected $test_model;

        public function __construct(test $test) {
            $this->test_model = $test;
        }

        public function dump_config($args = []) {
            if (isset($args[1])) {
                echo print_r($this->config->get($args[1]), 1)."\n";
            }

            return 0;
        }

        public function show_test_records() {
            echo print_r($this->test_model->all()->to_array(), true)."\n";
        }
    }
