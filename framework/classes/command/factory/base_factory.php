<?php
    namespace framework\command\factory;

    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\factory_interface;

    class base_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            if (method_exists($this, 'create')) {
                $class = $this->create($container, $class);
            } else {
                $class = $container->resolve($class);
            }

            $this->set_objects($container, $class);
            return $class;
        }

        protected function set_objects($container, $class) {
            $class->set_config($container->get('config'));
        }
    }
