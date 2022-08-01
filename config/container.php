<?php
    return [
        'store_instances' => [
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
            mvc\view::class => mvc\factory\view_factory::class,
            db\interfaces\db_interface::class => db\factory\db_factory::class,
            db\sql_builder::class => db\factory\sql_builder_factory::class,
            controller\login::class => controller\factory\base_factory::class,
            controller\main::class => controller\factory\base_factory::class,
            controller\form_test::class => controller\factory\base_factory::class,
            controller\records::class => controller\factory\base_factory::class,
            controller\table_crud::class => controller\factory\base_factory::class,
            controller\not_found::class => controller\factory\base_factory::class,
            command\test_commands::class => command\factory\base_factory::class,
            command\default_command::class => command\factory\base_factory::class,
            command\repl::class => command\factory\base_factory::class
        ]
    ];
