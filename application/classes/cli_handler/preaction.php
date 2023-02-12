<?php
    namespace application\cli_handler;

    use framework\mvc\interfaces\cli_handler\preaction_interface;

    class preaction implements preaction_interface {
        public function process(&$matched_route) {
            echo 'PREACTION: '.print_r($matched_route, 1);
            //$matched_route[0] = 'repl';
        }

    }

