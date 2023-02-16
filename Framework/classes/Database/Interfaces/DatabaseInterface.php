<?php

namespace Framework\Database\Interfaces;

/**
 * Database interface
 *
 * @package Framework\Database\Interfaces
 * @property bool $debugPrint
 * @property bool $debugLog
 * @property mixed|null $result
 */
interface DatabaseInterface
{
    /**
     * Connect to DB
     */
    public function connect();

    /**
     * Disconnect from DB
     */
    public function disconnect();

    /**
     * Execute a SQL query and store result in $this->result
     *
     * @param string $query
     * @return bool
     */
    public function query(string $query): bool;

    /**
     * Prepare an SQL query
     *
     * @param string $query
     * @return bool
     */
    public function prepare(string $query): bool;

    /**
     * Execute the prepared query and store result in $this->result
     *
     * @param array $types Array of data types for prepared parameters
     * @param array $data Array of data for prepared parameters
     * @return bool
     */
    public function execute(array $types, array $data): bool;

    /**
     * Prepare and execute an SQL query and store result in $this->result
     *
     * @param string $query SQL query
     * @param array $types Array of data types for prepared parameters
     * @param array $data Array of data for prepared parameters
     * @return bool
     */
    public function preparedQuery(string $query, array $types, array $data): bool;

    /**
     * Start a transaction
     */
    public function startTransaction();

    /**
     * Commit a transaction
     */
    public function commit();

    /**
     * Rollback a transaction
     */
    public function rollback();

    /**
     * Escape a string on the server
     *
     * @param string $value String to escape
     * @return string
     */
    public function escape(string $value);

    /**
     * Returns last error
     *
     * @return string
     */
    public function lastError();

    /**
     * Get ID of last inserted record
     *
     * @return bool|int
     */
    public function getLastInsertId();
}

