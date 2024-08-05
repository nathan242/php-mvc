<?php
return [
    'options' => [
        'trace' => true,
        'stream_context' => stream_context_create(
            [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ]
        )
    ],
    'server' => [
        'namespace' => 'Soap\Server'
    ]
];

