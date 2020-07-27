<?php
    return [
        'store_instances' => [
            mvc\config::class,
            mvc\request::class,
            mvc\response::class,
            mvc\router::class,
            mvc\command::class,
            mvc\session::class
        ],
        'aliases' => [
            'config' => mvc\config::class,
            'request' => mvc\request::class,
            'response' => mvc\response::class,
            'router' => mvc\router::class,
            'command' => mvc\command::class,
            'session' => mvc\session::class
        ],
        'factories' => [
            mvc\router::class => mvc\factory\router_factory::class,
            mvc\command::class => mvc\factory\command_factory::class,
            mvc\session::class => mvc\factory\session_factory::class,
            controller\login::class => controller\factory\base_factory::class,
            controller\main::class => controller\factory\base_factory::class,
            controller\form_test::class => controller\factory\base_factory::class,
            controller\records::class => controller\factory\base_factory::class,
            command\test_commands::class => command\factory\base_factory::class,
            command\initialization_commands::class => command\factory\initialization_commands_factory::class,
            model\user::class => model\factory\user_factory::class
        ]
    ];
