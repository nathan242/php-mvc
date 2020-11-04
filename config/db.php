<?php
    use db\mdb;
    use db\sqlite3db;
    use db\mysql_builder;
    use db\sqlite3_builder;

    return [
        'driver' => mdb::class,
        'sql_builder' => mysql_builder::class,
        'host' => 'localhost',
        'username' => 'php_mvc',
        'password' => 'P4p_Mvc!!2019',
        'db' => 'php_mvc'
    ];

//    return [
//        'driver' => sqlite3db::class,
//        'sql_builder' => sqlite3_builder::class,
//        'db_filename' => '/srv/sqlite3/database.sqlite3'
//    ];
