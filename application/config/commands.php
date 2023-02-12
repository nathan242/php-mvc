<?php
    return [
        'default' => ['\framework\command\default_command', 'list_commands'],
        'commands' => [
            'repl' => ['\framework\command\repl', 'shell', 'Start interactive shell'],
            'create-users-table' => ['initialization_commands', 'create_users_table', 'Create users table'],
            'create-test-table' => ['initialization_commands', 'create_test_table', 'Create test table'],
            'dump-config' => ['test_commands', 'dump_config', 'Dump configuration of specified type'],
            'show-test-records' => ['test_commands', 'show_test_records', 'Show records in the test table'],
            'no_method' => ['test_commands', 'no_exist', 'Test missing method'],
            'no_controller' => ['no_exist', 'test', 'Test missing controller']
        ]
    ];

