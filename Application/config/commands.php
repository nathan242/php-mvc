<?php
return [
    'default' => ['\Framework\Command\DefaultCommand', 'listCommands'],
    'commands' => [
        'repl' => ['\Framework\Command\Repl', 'shell', 'Start interactive shell'],
        'create-users-table' => ['InitializationCommands', 'createUsersTable', 'Create users table'],
        'create-test-table' => ['InitializationCommands', 'createTestTable', 'Create test table'],
        'dump-config' => ['TestCommands', 'dumpConfig', 'Dump configuration of specified type'],
        'show-test-records' => ['TestCommands', 'showTestRecords', 'Show records in the test table'],
        'no_method' => ['TestCommands', 'noExist', 'Test missing method'],
        'no_controller' => ['noExist', 'test', 'Test missing controller']
    ]
];

