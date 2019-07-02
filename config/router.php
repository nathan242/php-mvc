<?php
    return [
        'namespace' => 'controller',
        'routes' => [
            'GET' => [
                '/' => ['test', 'hello'],
                '/test/(\d+)' => ['test', 'number'],
                '/view' => ['test', 'view'],
                '/nocontroller' => ['testx', 'number'],
                '/nomethod' => ['test', 'noexist'],
                '/phpinfo' => ['test', 'php_info'],
                '/dump/server' => ['test', 'dump_server']
            ]
        ]
    ];

