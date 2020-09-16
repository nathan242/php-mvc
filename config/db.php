<?php
    use db\mdb;
    use db\mysql_builder;

    return [
        'driver' => mdb::class,
        'sql_builder' => mysql_builder::class,
        'host' => 'localhost',
        'username' => 'php_mvc',
        'password' => 'P4p_Mvc!!2019',
        'db' => 'php_mvc'
    ];
