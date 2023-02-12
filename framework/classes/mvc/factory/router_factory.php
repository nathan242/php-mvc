<?php
    namespace framework\mvc\factory;

    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\factory_interface;

    class router_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            $config = $container->get('config');
            return new $class($config->get('router'));
        }
    }
