<?php
    namespace mvc;

    use Exception;
    use RuntimeException;

    class container {
        private $aliases = [];
        private $factories = [];
        private $instances = [];

        public function __construct($config = []) {
            if (array_key_exists('aliases', $config)) {
                $this->aliases = $config['aliases'];
            }

            if (array_key_exists('factories', $config)) {
                $this->factories = $config['factories'];
            }
        }

	    public function create($name) {
            if (array_key_exists($name, $this->aliases)) {
                $name = $this->aliases[$name];
            }

            if (array_key_exists($name, $this->factories)) {
                $object = new $this->factories[$name]();
                $object = $object($this, $name);
            } else {
                $object = new $name();
            }

            return $object;
        }

        public function set($name, $object) {
            $this->instances[$name] = $object;
        }

        public function get($name) {
            try {
                if (array_key_exists($name, $this->instances)) {
                    return $this->instances[$name];
                } else {
                    $object = $this->create($name);
                    $this->set($name, $object);

                    return $object;
                }
            } catch (Exception $e) {
                throw new RuntimeException("Cannot get {$name} from container");
            }
        }
    }

