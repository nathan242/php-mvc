<?php
    namespace mvc;

    use mvc\interfaces\container_interface;
    use ReflectionClass;
    use Exception;
    use RuntimeException;
    use ReflectionException;

    class container implements container_interface {
        private $aliases = [];
        private $factories = [];
        private $store_instances = [];
        private $instances = [];

        public function __construct($config = []) {
            if (array_key_exists('aliases', $config)) {
                $this->aliases = $config['aliases'];
            }

            if (array_key_exists('factories', $config)) {
                $this->factories = $config['factories'];
            }

            if (array_key_exists('store_instances', $config)) {
                $this->store_instances = $config['store_instances'];
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
                $object = $this->resolve($name);
            }

            return $object;
        }

        public function set($name, $object) {
            $this->instances[$name] = $object;
        }

        public function get($name) {
            if (array_key_exists($name, $this->aliases)) {
                $name = $this->aliases[$name];
            }

            try {
                if (array_key_exists($name, $this->instances)) {
                    return $this->instances[$name];
                } else {
                    $object = $this->create($name);

                    if (in_array($name, $this->store_instances, true)) {
                        $this->set($name, $object);
                    }

                    return $object;
                }
            } catch (Exception $e) {
                throw new RuntimeException("Cannot get {$name} from container");
            }
        }

        public function resolve($name) {
            try {
                $reflection = new ReflectionClass($name);
            } catch (ReflectionException $e) {
                throw new RuntimeException("Class {$name} not found");
            }

            $constructor = $reflection->getConstructor();
            if (null === $constructor) {
                return new $name();
            }

            $parameters = $constructor->getParameters();

            $dependencies = [];
            foreach ($parameters as $parameter) {
                $dependencies[] = $this->get((string)$parameter->getType());
            }

            return new $name(...$dependencies);
        }
    }

