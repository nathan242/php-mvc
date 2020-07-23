<?php
    namespace mvc\interfaces;

    interface factory_interface {
        public function __invoke(container_interface $container, $class);
    }
