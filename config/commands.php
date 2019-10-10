<?php
    return [
        'namespace' => 'commands',
        'commands' => [
            'test' => ['test_commands', 'test', 'Hello world test command'],
            'dump-config' => ['test_commands', 'dump_config', 'Dump configuration of specified type']
        ],
        'factories' => [
            'test_commands' => 'factory\\base_factory'
        ]
    ];

