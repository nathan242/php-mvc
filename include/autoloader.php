<?php
// Class autoloader
spl_autoload_register(
    function ($class) {
        $class = str_replace('\\', '/', $class);
        require ROOT_PATH.'/classes/'.$class.'.php';
    }
);

