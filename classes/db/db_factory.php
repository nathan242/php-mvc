<?php
    namespace db;

    class db_factory {
        public static function get($config) {
            $db_config = $config->get('db');
            return [
                'driver' => new $db_config['driver']($db_config['host'], $db_config['username'], $db_config['password'], $db_config['db']),
                'sql_builder' => new $db_config['sql_builder']()
            ];
        }
    }
