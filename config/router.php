<?php
    return [
        'namespace' => 'controller',
        'routes' => [
            'GET' => [
                '/' => ['login', 'login'],
                '/logout' => ['login', 'logout'],
                '/main' => ['main', 'main'],
                '/form_test' => ['form_test', 'get']
            ],
            'POST' => [
                '/' => ['login', 'login'],
                '/form_test' => ['form_test', 'post']
            ]
        ],
        'factories' => [
            'login' => 'factory\\base_factory',
            'main' => 'factory\\base_factory',
            'form_test' => 'factory\\form_test_factory'
        ]
    ];

