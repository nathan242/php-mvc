<?php
    namespace db;

    class db_factory {
        public static function get($config) {
            $db_config = $config->get('db');
            return new mdb($db_config['host'], $db_config['username'], $db_config['password'], $db_config['db']);
        }
    }
