<?php
    return [
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
            controller\form_test::class => controller\factory\form_test_factory::class,
            controller\records::class => controller\factory\records_factory::class,
            commands\test_commands::class => commands\factory\base_factory::class
        ]
    ];
