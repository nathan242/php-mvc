<?php
    namespace framework\mvc\factory;

    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\factory_interface;

    class cli_handler_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            $config = $container->get('config');
            return new $class($container, $container->get('command'), $config->get('cli_handler'));
        }
    }
