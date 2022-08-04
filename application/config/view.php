<?php
    $local_config = $local['view'] ?? [];

    return [
        'path' => $local_config['path'] ?? "{$local['root_path']}/view/"
    ];
