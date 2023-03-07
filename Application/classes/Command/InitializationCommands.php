<?php

namespace Application\Command;

use Framework\Command\BaseCommand;
use Framework\Database\Interfaces\DatabaseInterface;
use Framework\Database\SqlBuilder;

/**
 * Initialization commands for the test application
 *
 * @package Application\Command
 */
class InitializationCommands extends BaseCommand
{
    /** @var DatabaseInterface $db */
    protected $db;

    /** @var SqlBuilder $sqlBuilder */
    protected $sqlBuilder;

    /**
     * InitializationCommands constructor
     *
     * @param DatabaseInterface $db
     * @param SqlBuilder $sqlBuilder
     */
    public function __construct(DatabaseInterface $db, SqlBuilder $sqlBuilder)
    {
        $this->db = $db;
        $this->sqlBuilder = $sqlBuilder;
    }

    /**
     * Create users table and insert admin user
     *
     * @param array $args
     * @return int
     */
    public function createUsersTable(array $args = []): int
    {
        echo "Creating users table ... ";

        $sql = $this->sqlBuilder
            ->reset()
            ->create('users', ['mysql_engine' => 'InnoDB', 'charset' => 'latin1'])
            ->field('id', 'int', ['unsigned' => true, 'required' => true, 'increment' => true, 'primary' => true])
            ->field('username', 'string', ['required' => true, 'unique' => true])
            ->field('password', 'string', ['required' => true])
            ->field('enabled', 'boolean', ['required' => true, 'default' => 0])
            ->sql();

        if (!$this->db->query($sql['sql'])) {
            echo "Failed\n\nFailed to create user table.\nError: " . $this->db->lastError() . "\n";
            return 1;
        }

        echo "Done\n";

        echo "Inserting admin user ... ";

        $sql = $this->sqlBuilder
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

        if (!$this->db->preparedQuery($sql['sql'], $sql['types'], $sql['params'])) {
            echo "Failed\n\nFailed to insert admin user.\nError: " . $this->db->lastError() . "\n";
            return 1;
        }

        echo "Done\n";

        return 0;
    }

    /**
     * Create test table
     *
     * @param array $args
     * @return int
     */
    public function createTestTable(array $args = []): int
    {
        echo "Creating test table ... ";

        $sql = $this->sqlBuilder
            ->reset()
            ->create('test', ['mysql_engine' => 'InnoDB', 'charset' => 'latin1'])
            ->field('id', 'int', ['unsigned' => true, 'required' => true, 'increment' => true, 'primary' => true])
            ->field('text', 'string')
            ->field('number', 'int')
            ->sql();

        if (!$this->db->query($sql['sql'])) {
            echo "Failed\n\nFailed to create test table.\nError: " . $this->db->lastError() . "\n";
            return 1;
        }

        echo "Done\n";

        return 0;
    }
}

