<?php
    namespace command;

    use mvc\container;

    class repl extends base_command {
        protected $container;

        public function __construct(container $container) {
            $this->container = $container;
        }

        public function shell() {
            echo "Starting shell\n";
            require $this->config->local['root_path'].'/include/repl.php';
        }
    }
