<?php
// Class autoloader
if (!isset($rootPath)) {
    throw new RuntimeException('Root path not set when setting up autoloader');
}

spl_autoload_register(
    function ($class) use ($rootPath) {
        $class = str_replace('\\', '/', $class);

        $parts = preg_split('/(?<=[a-z0-9])\//', $class);
        $parts[0] = preg_replace('/^\//', '', $parts[0]);
        $base = array_shift($parts);
        $class = implode('/', $parts);

        $path = "{$rootPath}/../{$base}/classes/{$class}.php";

        if (file_exists($path)) {
            include $path;
        }
    }
);

