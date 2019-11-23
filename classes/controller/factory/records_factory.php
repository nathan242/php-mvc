<?php
    namespace controller\factory;

    use gui\form;

    class records_factory extends base_factory {
        protected function create($controller) {
            return new $controller(new form());
        }
    }