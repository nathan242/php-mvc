<?php
    namespace framework\mvc\interfaces\web_handler;

    interface preaction_interface {
        public function process(&$matched_route);
    }
