<?php
    return [
        'namespace' => 'controller',
        'routes' => [
            'GET' => [
                '/' => ['login', 'login'],
                '/logout' => ['login', 'logout'],
                '/main' => ['main', 'main'],
                '/form_test' => ['form_test', 'get'],
                '/records' => ['records', 'list_all'],
                '/records/add' => ['records', 'create'],
                '/records/(\d+)' => ['records', 'edit']
            ],
            'POST' => [
                '/' => ['login', 'login'],
                '/form_test' => ['form_test', 'post'],
                '/records/add' => ['records', 'create'],
                '/records/(\d+)' => ['records', 'edit']
            ]
        ]
    ];

