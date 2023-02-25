<?php

if (!isset($local)) {
    throw new RuntimeException('Local config not passed into application config');
}

return [
    'name' => 'PHP-MVC Test Application',
    'version' => 'v0.0.1',

    'upload_dir' => "{$local['root_path']}/uploads"
];

