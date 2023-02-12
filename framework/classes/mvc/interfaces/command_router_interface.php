<?php
    namespace framework\mvc\interfaces;

    interface command_router_interface {
        public function command($name, $action);
        public function process($arguments);
    }

