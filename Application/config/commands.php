<?php
return [
    'default' => ['\Framework\Command\DefaultCommand', 'listCommands'],
    'commands' => [
        'repl' => ['\Framework\Command\Repl', 'shell', 'Start interactive shell'],
        'create-users-table' => ['InitializationCommands', 'create_users_table', 'Create users table'],
        'create-test-table' => ['InitializationCommands', 'create_test_table', 'Create test table'],
        'dump-config' => ['TestCommands', 'dump_config', 'Dump configuration of specified type'],
        'show-test-records' => ['TestCommands', 'show_test_records', 'Show records in the test table'],
        'no_method' => ['TestCommands', 'no_exist', 'Test missing method'],
        'no_controller' => ['no_exist', 'test', 'Test missing controller']
    ]
];

