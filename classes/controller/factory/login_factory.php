<?php
    namespace controller\factory;

    use mvc\object_storage;

    class login_factory {
        public function __invoke($controller) {
            return new $controller(object_storage::get('request'), object_storage::get('session'), object_storage::get('config'));
        }
    }
