<?php
    $local_config = $local['application'] ?? [];

    return [
        'name' => $local_config['name'] ?? 'PHP-MVC',
        'version' => $local_config['version'] ?? 'v0.0.1'
    ];

