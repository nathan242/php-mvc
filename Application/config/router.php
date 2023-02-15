<?php
return [
    'routes' => [
        'GET' => [
            '/' => ['Login', 'login'],
            '/logout' => ['Login', 'logout'],
            '/main' => ['Main', 'main'],
            '/form_test' => ['FormTest', 'get'],
            '/records' => ['Records', 'list_all'],
            '/records/add' => ['Records', 'create'],
            '/records/(\d+)' => ['Records', 'edit'],
            '/table_crud' => ['TableCrud', 'list_all'],
            '/table_crud/add' => ['TableCrud', 'create'],
            '/table_crud/(\d+)' => ['TableCrud', 'edit'],
            '/file_upload' => ['Files', 'index'],
            '/file_upload/(.+)' => ['Files', 'download'],
            '/cookies' => ['Cookies', 'index'],
            '/no_method' => ['Main', 'no_exist'],
            '/no_controller' => ['no_exist', 'test'],
        ],
        'POST' => [
            '/' => ['Login', 'login'],
            '/form_test' => ['FormTest', 'post'],
            '/records/add' => ['Records', 'create'],
            '/records/(\d+)' => ['Records', 'edit'],
            '/table_crud/add' => ['TableCrud', 'create'],
            '/table_crud/(\d+)' => ['TableCrud', 'edit'],
            '/file_upload' => ['Files', 'upload'],
            '/api_post' => ['Api', 'post_test'],
            '/cookies' => ['Cookies', 'save'],
        ],
        '*' => [
            '/api' => ['Api', 'api_test'],
            '/(.*)' => ['NotFound', 'error_404']
        ]
    ]
];

