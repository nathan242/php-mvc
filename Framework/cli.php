<?php

use Framework\Mvc\Application;

$rootPath = __DIR__;

require_once "{$rootPath}/../Framework/include/autoloader.php";

exit((new Application())->init($rootPath)->runCli($argv));
