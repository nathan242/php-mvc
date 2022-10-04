<?php
    return [
        'namespace' => 'application\controller',
        'routes' => [
            'GET' => [
                '/' => ['login', 'login'],
                '/logout' => ['login', 'logout'],
                '/main' => ['main', 'main'],
                '/form_test' => ['form_test', 'get'],
                '/records' => ['records', 'list_all'],
                '/records/add' => ['records', 'create'],
                '/records/(\d+)' => ['records', 'edit'],
                '/table_crud' => ['table_crud', 'list_all'],
                '/table_crud/add' => ['table_crud', 'create'],
                '/table_crud/(\d+)' => ['table_crud', 'edit'],
                '/file_upload' => ['files', 'index'],
                '/file_upload/(.+)' => ['files', 'download'],
                '/no_method' => ['main', 'no_exist'],
                '/no_controller' => ['no_exist', 'test'],
            ],
            'POST' => [
                '/' => ['login', 'login'],
                '/form_test' => ['form_test', 'post'],
                '/records/add' => ['records', 'create'],
                '/records/(\d+)' => ['records', 'edit'],
                '/table_crud/add' => ['table_crud', 'create'],
                '/table_crud/(\d+)' => ['table_crud', 'edit'],
                '/file_upload' => ['files', 'upload'],
                '/api_post' => ['api', 'post_test'],
            ],
            '*' => [
                '/api' => ['api', 'api_test'],
                '/(.*)' => ['not_found', 'error_404']
            ]
        ]
    ];

