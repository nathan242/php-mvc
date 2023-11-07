<?php

use Framework\Mvc\Application;

$rootPath = __DIR__ . '/..';

//require_once "{$rootPath}/../Framework/include/autoloader.php";
require_once "{$rootPath}/vendor/autoload.php";

$configFile = "{$rootPath}/config.php";
$localConfig = [];
if (file_exists($configFile)) {
    $localConfig = require $configFile;
}

$application = new Application();
$application->init($rootPath, $localConfig);

if ('cli' === php_sapi_name()) {
    exit($application->runCli($argv));
}

$application->runWeb();

