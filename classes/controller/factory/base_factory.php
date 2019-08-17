<?php
    namespace controller\factory;

    use mvc\object_storage;
    use db\db_factory;
    use model\user;

    class base_factory {
        public function __invoke($controller) {
            if (method_exists($this, 'create')) {
                $controller = $this->create($controller);
            } else {
                $controller = new $controller();
            }

            $this->set_objects($controller);
            return $controller;
        }

        protected function set_objects($controller) {
            $controller->set_request(object_storage::get('request'));
            $controller->set_session(object_storage::get('session'));
            $controller->set_config(object_storage::get('config'));
            $controller->set_user(new user(db_factory::get(object_storage::get('config')), object_storage::get('session')));
        }
    }
