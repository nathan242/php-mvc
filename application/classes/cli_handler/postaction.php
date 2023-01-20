<?php
    namespace application\cli_handler;

    use framework\mvc\interfaces\cli_handler\postaction_interface;

    class postaction implements postaction_interface {
        public function process(&$response) {
            echo 'POSTACTION: '.print_r($response, 1);
            //$response = 255;
        }
    }

