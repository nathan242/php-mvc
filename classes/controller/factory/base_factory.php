<?php
    namespace controller\factory;

    use mvc\object_storage;
    use model\user;

    class base_factory {
        public function __invoke($controller) {
            if (method_exists($this, 'create')) {
                $controller = $this->create($controller);
            } else {
                $controller = new $controller();
            }

            $this->set_objects($controller);
            $this->run_init($controller);
            return $controller;
        }

        protected function set_objects($controller) {
            $controller->set_request(object_storage::get('request'));
            $controller->set_session(object_storage::get('session'));
            $controller->set_config(object_storage::get('config'));
            $controller->set_user(new user(object_storage::get('db'), object_storage::get('session')));
        }

        protected function run_init($controller) {
            if (method_exists($controller, 'init')) {
                $controller->init();
            }
        }
    }
