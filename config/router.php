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
        ],
        'factories' => [
            'login' => 'factory\\base_factory',
            'main' => 'factory\\base_factory',
            'form_test' => 'factory\\form_test_factory',
            'records' => 'factory\\records_factory'
        ]
    ];

