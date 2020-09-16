<?php
    return [
        'namespace' => 'command',
        'commands' => [
            'create-users-table' => ['initialization_commands', 'create_users_table', 'Create users table'],
            'create-test-table' => ['initialization_commands', 'create_test_table', 'Create test table'],
            'dump-config' => ['test_commands', 'dump_config', 'Dump configuration of specified type']
        ]
    ];

