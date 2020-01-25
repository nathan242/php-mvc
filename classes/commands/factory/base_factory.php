<?php
    namespace commands\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class base_factory implements factory_interface {
        public function __invoke(container_interface $container, $controller) {
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
