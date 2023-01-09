<?php
    namespace framework\mvc\interfaces;

    interface router_interface {
        public function route($path, $method, $action);
        public function process(request_interface $request);
    }
