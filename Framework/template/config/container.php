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
        Framework\Mvc\Interfaces\ContainerInterface::class => Framework\Mvc\Container::class,
        Framework\Mvc\Interfaces\ConfigInterface::class => Framework\Mvc\Config::class,
        Framework\Mvc\Interfaces\RequestInterface::class => Framework\Mvc\Request::class,
        Framework\Mvc\Interfaces\ResponseInterface::class => Framework\Mvc\Response::class,
        Framework\Mvc\Interfaces\RouterInterface::class => Framework\Mvc\Router::class,
        Framework\Mvc\Interfaces\CommandRouterInterface::class => Framework\Mvc\Command::class,
        Framework\Mvc\Interfaces\SessionInterface::class => Framework\Mvc\Session::class,
        Framework\Mvc\Interfaces\ViewInterface::class => Framework\Mvc\View::class
    ],
    'factories' => [
        Framework\Mvc\WebHandler::class => Framework\Mvc\Factory\WebHandlerFactory::class,
        Framework\Mvc\CliHandler::class => Framework\Mvc\Factory\CliHandlerFactory::class,
        Framework\Mvc\Router::class => Framework\Mvc\Factory\RouterFactory::class,
        Framework\Mvc\Command::class => Framework\Mvc\Factory\CommandFactory::class,
        Framework\Mvc\View::class => Framework\Mvc\Factory\ViewFactory::class,
        Framework\Database\Interfaces\DatabaseInterface::class => Framework\Database\Factory\DbFactory::class,
        Framework\Database\SqlBuilder::class => Framework\Database\Factory\SqlBuilderFactory::class,
        Framework\Command\DefaultCommand::class => Framework\Command\Factory\BaseFactory::class
    ]
];
