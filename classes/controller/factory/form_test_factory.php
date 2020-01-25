<?php
    namespace controller\factory;

    use gui\form;
    use mvc\interfaces\container_interface;

    class form_test_factory extends base_factory {
        protected function create(container_interface $container, $controller) {
            return new $controller(new form());
        }
    }