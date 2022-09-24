<?php
    use framework\mvc\application;

    $root_path = __DIR__.'/..';

    require_once "{$root_path}/../framework/include/autoloader.php";

    $config_file = "{$root_path}/config.php";
    $local_config = [];
    if (file_exists($config_file)) {
        $local_config = require $config_file;
    }

    $application = new application();
    $application->init($root_path, $local_config);

    if ('cli' === php_sapi_name()) {
        exit($application->run_cli($argv));
    }

    $application->run_web();

