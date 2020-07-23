<?php
    namespace mvc\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class command_factory implements factory_interface {

        public function __invoke(container_interface $container, $class) {
            $config = $container->get('config');
            return new $class($container, $config->get('commands'), $config->get('application'));
        }
    }
