<?php
    return [
        'namespace' => 'controller',
        'routes' => [
            'GET' => [
                '/' => ['login', 'login'],
                '/logout' => ['login', 'logout']
            ],
            'POST' => [
                '/' => ['login', 'login']
            ]
        ]
    ];

