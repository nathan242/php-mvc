<?php
    $local_config = $local['application'] ?? [];

    return [
        'name' => $local_config['name'] ?? 'New Application',
        'version' => $local_config['version'] ?? 'v0.0.0'
    ];

