<?php
    namespace mvc;

    use RuntimeException;

    class container {
	    private $storage = [];

        public function add($name, $object) {
                $this->storage[$name] = $object;
        }

        public function get($name) {
            if (array_key_exists($name, $this->storage)) {
                return $this->storage[$name];
            }

            throw new RuntimeException("Cannot get {$name} from container");
        }
    }

