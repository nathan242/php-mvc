<?php
    namespace commands\factory;

    use mvc\object_storage;

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
            $controller->set_config(object_storage::get('config'));
        }
    }
