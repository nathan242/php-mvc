<?php
    namespace mvc\interfaces;

    interface factory {
        public function __invoke($container, $controller);
    }