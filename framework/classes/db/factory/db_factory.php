<?php
    namespace framework\db\factory;

    use framework\mvc\interfaces\container_interface;
    use framework\mvc\interfaces\factory_interface;

    class db_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            if ($container->has_instance('db_driver')) {
                return $container->get('db_driver');
            }

            $config = $container->get('config');
            $db_config = $config->get('db');
            $db = new $db_config['driver']($db_config);
            $container->set('db_driver', $db);

            return $db;
        }
    }

