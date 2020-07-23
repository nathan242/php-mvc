<?php
    namespace mvc\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class session_factory implements factory_interface {

        public function __invoke(container_interface $container, $class) {
            $config = $container->get('config');
            return new $class($config->get('application')['name']);
        }
    }
