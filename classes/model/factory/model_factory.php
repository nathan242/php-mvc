<?php
    namespace model\factory;

    use mvc\interfaces\container_interface;
    use mvc\interfaces\factory_interface;
    use model\model_collection;

    class model_factory implements factory_interface {
        public function __invoke(container_interface $container, $class) {
            return new $class($container->get('db_driver'), $container->get('db_sql_builder'), $container->get(model_collection::class));
        }
    }

