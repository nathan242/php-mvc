<?php
    namespace application\cli_handler;

    use framework\mvc\interfaces\cli_handler\preroute_interface;

    class preroute implements preroute_interface {
        public function process(&$arguments) {
            echo 'PREROUTE';
            //$arguments[] = 'repl';
        }
    }

