<?php
    return [
        'namespace' => 'controller',
        'routes' => [
            'GET' => [
                '/' => ['login', 'login'],
                '/logout' => ['login', 'logout'],
                '/main' => ['main', 'main']
            ],
            'POST' => [
                '/' => ['login', 'login']
            ]
        ],
        'factories' => [
            'login' => 'factory\\base_factory',
            'main' => 'factory\\base_factory'
        ]
    ];

