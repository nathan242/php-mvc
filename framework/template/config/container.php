<?php
    return [
        'store_instances' => [
            framework\mvc\request::class,
            framework\mvc\response::class,
            framework\mvc\router::class,
            framework\mvc\command::class,
            framework\mvc\session::class
        ],
        'aliases' => [
            'config' => framework\mvc\config::class,
            'request' => framework\mvc\request::class,
            'response' => framework\mvc\response::class,
            'web_handler' => framework\mvc\web_handler::class,
            'cli_handler' => framework\mvc\cli_handler::class,
            'router' => framework\mvc\router::class,
            'command' => framework\mvc\command::class,
            'session' => framework\mvc\session::class
        ],
        'factories' => [
            framework\mvc\web_handler::class => framework\mvc\factory\web_handler_factory::class,
            framework\mvc\cli_handler::class => framework\mvc\factory\cli_handler_factory::class,
            framework\mvc\router::class => framework\mvc\factory\router_factory::class,
            framework\mvc\command::class => framework\mvc\factory\command_factory::class,
            framework\mvc\view::class => framework\mvc\factory\view_factory::class,
            framework\db\interfaces\db_interface::class => framework\db\factory\db_factory::class,
            framework\db\sql_builder::class => framework\db\factory\sql_builder_factory::class,
            framework\command\default_command::class => framework\command\factory\base_factory::class,
        ]
    ];
