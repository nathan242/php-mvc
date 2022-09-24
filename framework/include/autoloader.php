<?php
// Class autoloader
spl_autoload_register(
    function ($class) use ($root_path) {
        $class = str_replace('\\', '/', $class);

        $parts = preg_split('/(?<=[a-z0-9])\//', $class);
        $parts[0] = preg_replace('/^\//', '', $parts[0]);
        $base = array_shift($parts);
        $class = implode('/', $parts);

        $path = "{$root_path}/../{$base}/classes/{$class}.php";

        if (file_exists($path)) {
            include $path;
        }
    }
);

