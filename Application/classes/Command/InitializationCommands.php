<?php

namespace Application\Command;

use Framework\Command\BaseCommand;
use Framework\Database\Interfaces\DatabaseInterface;
use Framework\Database\SqlBuilder;

class InitializationCommands extends BaseCommand
{
    protected $db;
    protected $sql_builder;

    public function __construct(DatabaseInterface $db, SqlBuilder $sql_builder)
    {
        $this->db = $db;
        $this->sql_builder = $sql_builder;
    }

    public function create_users_table($args = [])
    {
        echo "Creating users table ... ";

        $sql = $this->sql_builder
            ->reset()
            ->create('users', ['mysql_engine' => 'InnoDB', 'charset' => 'latin1'])
            ->field('id', 'int', ['unsigned' => true, 'required' => true, 'increment' => true, 'primary' => true])
            ->field('username', 'string', ['required' => true, 'unique' => true])
            ->field('password', 'string', ['required' => true])
            ->field('enabled', 'boolean', ['required' => true, 'default' => 0])
            ->sql();

        if (!$this->db->query($sql['sql'])) {
            echo "Failed\n\nFailed to create user table.\nError: " . $this->db->last_error() . "\n";
            return 1;
        }

        echo "Done\n";

        echo "Inserting admin user ... ";

        $sql = $this->sql_builder
            ->reset()
            ->insert(
                [
                    'username' => 'admin',
                    'password' => '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918',
                    'enabled' => 1
                ]
            )
            ->into('users')
            ->sql();

        if (!$this->db->prepared_query($sql['sql'], $sql['types'], $sql['params'])) {
            echo "Failed\n\nFailed to insert admin user.\nError: " . $this->db->last_error() . "\n";
            return 1;
        }

        echo "Done\n";

        return 0;
    }

    public function create_test_table($args = [])
    {
        echo "Creating test table ... ";

        $sql = $this->sql_builder
            ->reset()
            ->create('test', ['mysql_engine' => 'InnoDB', 'charset' => 'latin1'])
            ->field('id', 'int', ['unsigned' => true, 'required' => true, 'increment' => true, 'primary' => true])
            ->field('text', 'string')
            ->field('number', 'int')
            ->sql();

        if (!$this->db->query($sql['sql'])) {
            echo "Failed\n\nFailed to create test table.\nError: " . $this->db->last_error() . "\n";
            return 1;
        }

        echo "Done\n";
    }
}

