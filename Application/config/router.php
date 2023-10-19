<?php
return [
    'routes' => [
        'GET' => [
            '/' => ['Login', 'login'],
            '/logout' => ['Login', 'logout'],
            '/main' => ['Main', 'main'],
            '/form_test' => ['FormTest', 'get'],
            '/records' => ['Records', 'listAll'],
            '/records/add' => ['Records', 'create'],
            '/records/(\d+)' => ['Records', 'edit'],
            '/table_crud' => ['TableCrud', 'listAll'],
            '/table_crud/add' => ['TableCrud', 'create'],
            '/table_crud/(\d+)' => ['TableCrud', 'edit'],
            '/file_upload' => ['Files', 'index'],
            '/file_upload/(.+)' => ['Files', 'download'],
            '/cookies' => ['Cookies', 'index'],
            '/session' => ['Session', 'index'],
            '/no_method' => ['Login', 'noExist'],
            '/no_controller' => ['noExist', 'test'],
        ],
        'POST' => [
            '/' => ['Login', 'login'],
            '/form_test' => ['FormTest', 'post'],
            '/records/add' => ['Records', 'create'],
            '/records/(\d+)' => ['Records', 'edit'],
            '/table_crud/add' => ['TableCrud', 'create'],
            '/table_crud/(\d+)' => ['TableCrud', 'edit'],
            '/file_upload' => ['Files', 'upload'],
            '/api_post' => ['Api', 'postTest'],
            '/cookies' => ['Cookies', 'save'],
        ],
        '*' => [
            '/api' => ['Api', 'apiTest'],
            '/headers' => ['Api', 'headers'],
            '/(.*)' => ['NotFound', 'error404']
        ]
    ]
];

