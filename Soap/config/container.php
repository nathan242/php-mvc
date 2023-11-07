<?php
return [
    'store_instances' => [
        Framework\Mvc\Request::class,
        Framework\Mvc\Response::class,
        Framework\Mvc\Router::class,
        Framework\Mvc\Command::class,
        Framework\Mvc\Session::class
    ],
    'aliases' => [
        'config' => Framework\Mvc\Config::class,
        'request' => Framework\Mvc\Request::class,
        'response' => Framework\Mvc\Response::class,
        'web_handler' => Framework\Mvc\WebHandler::class,
        'cli_handler' => Framework\Mvc\CliHandler::class,
        'router' => Framework\Mvc\Router::class,
        'command' => Framework\Mvc\Command::class,
        'session' => Framework\Mvc\Session::class
    ],
    'factories' => [
        Framework\Mvc\WebHandler::class => Framework\Mvc\Factory\WebHandlerFactory::class,
        Framework\Mvc\CliHandler::class => Framework\Mvc\Factory\CliHandlerFactory::class,
        Framework\Mvc\Router::class => Framework\Mvc\Factory\RouterFactory::class,
        Framework\Mvc\Command::class => Framework\Mvc\Factory\CommandFactory::class,
        Framework\Mvc\View::class => Framework\Mvc\Factory\ViewFactory::class,
        Framework\Database\Interfaces\DatabaseInterface::class => Framework\Database\Factory\DbFactory::class,
        Framework\Database\SqlBuilder::class => Framework\Database\Factory\SqlBuilderFactory::class,
        Framework\Command\DefaultCommand::class => Framework\Command\Factory\BaseFactory::class,
        Soap\Controller\Client::class => Framework\Controller\Factory\BaseFactory::class,
        Soap\Controller\Server::class => Framework\Controller\Factory\BaseFactory::class,
        Soap\Command\Client::class => Framework\Command\Factory\BaseFactory::class,
        Soap\Soap\Client::class => Soap\Soap\Factory\ClientFactory::class,
        Soap\Soap\Server::class => Soap\Soap\Factory\ServerFactory::class
    ]
];
