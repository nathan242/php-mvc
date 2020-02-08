<?php
    return [
        'namespace' => 'commands',
        'commands' => [
            'create-users-table' => ['initialization_commands', 'create_users_table', 'Create the users table in the configured DB'],
            'dump-config' => ['test_commands', 'dump_config', 'Dump configuration of specified type']
        ]
    ];

