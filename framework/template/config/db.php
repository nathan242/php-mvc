<?php
    use framework\db\sqlite3db;
    use framework\db\sqlite3_builder;

    $local_config = $local['db'] ?? [];

    if ($local_config === []) {
        return [
            'driver' => sqlite3db::class,
            'sql_builder' => sqlite3_builder::class,
            'db_filename' => "{$local['root_path']}/database.sqlite3"
        ];
    }

    return $local_config;

