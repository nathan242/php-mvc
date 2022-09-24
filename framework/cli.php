<?php
    use framework\mvc\application;

    $root_path = __DIR__;

    require_once "{$root_path}/../framework/include/autoloader.php";

    exit((new application())->init($root_path)->run_cli($argv));

