<?php

use Framework\Database\Sqlite3Builder;
use Framework\Database\Sqlite3Db;

$localConfig = $local['db'] ?? [];

if ($localConfig === []) {
    return [
        'driver' => Sqlite3db::class,
        'sql_builder' => Sqlite3Builder::class,
        'db_filename' => "{$local['root_path']}/database.sqlite3"
    ];
}

return $localConfig;

