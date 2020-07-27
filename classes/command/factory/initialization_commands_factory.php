<?php
    namespace command\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;

    class initialization_commands_factory extends base_factory implements factory_interface {
        public function create(container_interface $container, $controller) {
            return new $controller($container->get('db'));
        }
    }
