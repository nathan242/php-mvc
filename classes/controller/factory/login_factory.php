<?php
    namespace controller\factory;

    class login_factory extends base_factory {
        public function create($controller) {
            return new $controller();
        }
    }
