<?php
    namespace framework\mvc\interfaces\cli_handler;

    interface preaction_interface {
        public function process(&$matched_route);
    }
