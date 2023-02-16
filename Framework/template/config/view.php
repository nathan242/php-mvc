<?php

if (!isset($local)) {
    throw new RuntimeException('Local config not passed into view config');
}

return [
    'path' => "{$local['root_path']}/view/"
];
