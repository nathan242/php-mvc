<?php
// Class autoloader
spl_autoload_register(
    function ($class) use ($root_path) {
        $class = str_replace('\\', '/', $class);
        $path = "{$root_path}/classes/{$class}.php";

        if (file_exists($path)) {
            include $path;
        }
    }
);

