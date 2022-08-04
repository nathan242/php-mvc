<?php
    namespace framework\command;

    use framework\mvc\container;

    class repl extends base_command {
        protected $container;

        public function __construct(container $container) {
            $this->container = $container;
        }

        public function shell($args = []) {
            echo "Starting shell\n";
            require $this->config->local['root_path'].'/../framework/include/repl.php';
        }
    }
