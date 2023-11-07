<?php
return [
    'routes' => [
        'GET' => [
            '/' => ['Client', 'client'],
            '/server/(.+)' => ['Server', 'wsdl']
        ],
        'POST' => [
            '/' => ['Client', 'client'],
            '/server/(.+)' => ['Server', 'server']
        ]
    ]
];

