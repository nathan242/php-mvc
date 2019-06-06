<?php
    namespace Mvc;

    class object_storage {
        private static $instance = null;
	private $storage = [];

	public static function get_instance() {
            if (null === self::$instance) {
                self::$instance = new self();
	    }

	    return self::$instance;
	}

	public function add_object($name, $object) {
            $this->storage[$name] = $object;
	}

	public function get_object($name) {
            if (array_key_exists($name, $this->storage)) {
                return $this->storage[$name];
            }

            return false;
	}

	public static function add($name, $object) {
            return self::get_instance()->add_object($name, $object);
	}

	public static function get($name) {
            return self::get_instance()->get_object($name);
	}
    }

