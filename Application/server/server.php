<?php

use Framework\Mvc\Application;

$rootPath = __DIR__ . '/..';

require_once "{$rootPath}/../Framework/include/autoloader.php";

$configFile = "{$rootPath}/config.php";
$localConfig = [];
if (file_exists($configFile)) {
    $localConfig = require $configFile;
}

$application = new Application();
$application->init($rootPath, $localConfig);

exit($application->runServer());

