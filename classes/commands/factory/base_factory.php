<?php
    namespace commands\factory;

    use mvc\interfaces\factory;

    class base_factory implements factory {
        public function __invoke($container, $controller) {
            if (method_exists($this, 'create')) {
                $controller = $this->create($container, $controller);
            } else {
                $controller = new $controller();
            }

            $this->set_objects($container, $controller);
            return $controller;
        }

        protected function set_objects($container, $controller) {
            $controller->set_config($container->get('config'));
        }
    }
