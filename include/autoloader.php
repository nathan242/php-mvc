<?php
// Class autoloader
spl_autoload_register(
    function ($class) use ($root_path) {
        $class = str_replace('\\', '/', $class);
        require "{$root_path}/classes/{$class}.php";
    }
);

