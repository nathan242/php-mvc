<?php
    use mvc\application;

    $root_path = __DIR__.'/..';

    require_once "{$root_path}/include/autoloader.php";

    $application = new application();
    $application->init($root_path);

    if ('cli' === php_sapi_name()) {
        exit($application->run_cli($argv));
    }

    $application->run_web();

