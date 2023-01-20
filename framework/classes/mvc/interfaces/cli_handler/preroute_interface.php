<?php
    namespace framework\mvc\interfaces\cli_handler;

    interface preroute_interface {
        public function process(&$arguments);
    }
