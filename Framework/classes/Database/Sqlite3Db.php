<?php

namespace Framework\Database;

use SQLite3;
use SQLite3Stmt;
use SQLite3Result;
use Framework\Database\Interfaces\DatabaseInterface;

/**
 * SQlite3 database connection
 *
 * @package Framework\Database
 */
class Sqlite3Db implements DatabaseInterface
{
    /** @var string $dbFilename */
    public $dbFilename;

    /** @var bool $debugPrint Enable to print debug messages */
    public $debugPrint = false;

    /** @var bool $debugLog Enable to log debug messages */
    public $debugLog = false;

    /** @var bool $keepConnected Enable to keep connection open after query */
    public $keepConnected = true;

    /** @var mixed|null $result Query result */
    public $result;

    /** @var SQLite3 $dbobj SQLite3 database object */
    protected $dbobj;

    /** @var bool $isConnected Connection status */
    protected $isConnected = false;

    /** @var bool $transactionOpen Transaction status */
    protected $transactionOpen = false;

    /** @var SQLite3Result|bool $qResult Query result */
    protected $qResult;

    /** @var SQLite3Stmt|null $stmt Prepared statement */
    protected $stmt;

    /**
     * Construct DB object
     *
     * @param array<string, mixed> $config
     */
    function __construct(array $config)
    {
        $this->dbFilename = $config['db_filename'] ?? null;
    }

    /**
     * Connect to DB
     */
    public function connect(): void
    {
        if (!$this->isConnected) {
            $this->dbobj = new SQLite3($this->dbFilename);
            $this->isConnected = true;
            $this->transactionOpen = false;
        }
    }

    /**
     * Disconnect from DB
     */
    public function disconnect(): void
    {
        if ($this->isConnected) {
            $this->dbobj->close();
            $this->isConnected = false;
            $this->transactionOpen = false;
        }
    }

    /**
     * Disconnect from the DB server if set to not remain connected or a transaction is not open
     */
    protected function disconnectIfAllowed(): void
    {
        if (!$this->keepConnected && !$this->transactionOpen) {
            $this->disconnect();
        }
    }

    /**
     * Execute a SQL query and store result in $this->result
     *
     * @param string $query
     * @return bool
     */
    public function query(string $query): bool
    {
        $this->connect();
        $this->debugPrint("QUERY = {$query}");

        if ($this->qResult = $this->dbobj->query($query)) {
            $this->result = $this->getResult($this->qResult);
            $this->disconnectIfAllowed();

            return true;
        } else {
            $this->disconnectIfAllowed();
            return false;
        }
    }

    /**
     * Prepare an SQL query
     *
     * @param string $query
     * @return bool
     */
    public function prepare(string $query): bool
    {
        $this->connect();
        $this->debugPrint("PREPARE_QUERY = {$query}");
        if (!$this->stmt = $this->dbobj->prepare($query)) {
            $this->disconnectIfAllowed();
            return false;
        }

        return true;
    }

    /**
     * Execute the prepared query and store result in $this->result
     *
     * @param array<mixed> $types Array of data types for prepared parameters
     * @param array<mixed> $data Array of data for prepared parameters
     * @return bool
     */
    public function execute(array $types, array $data): bool
    {
        $this->debugPrint('EXECUTE_PREPARED_QUERY | TYPES = ' . print_r($types, true) . ' | DATA = ' . print_r($data, true));

        $paramCount = count($types);

        for ($i = 0; $i < $paramCount; $i++) {
            if (!$this->stmt->bindValue($i + 1, $data[$i], $types[$i])) {
                $this->disconnectIfAllowed();
                return false;
            }
        }

        if (!$result = $this->stmt->execute()) {
            $this->disconnectIfAllowed();
            return false;
        }

        $this->result = $this->getResult($result);

        return true;
    }

    /**
     * Get results array
     *
     * @param SQLite3Result $result
     * @return array<int, array<string, mixed>>
     */
    private function getResult(SQLite3Result $result): array
    {
        $arr = [];

        if ($result->numColumns() !== 0) {
            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                $arr[] = $row;
            }
        }

        return $arr;
    }

    /**
     * Prepare and execute an SQL query and store result in $this->result
     *
     * @param string $query SQL query
     * @param array<mixed> $types Array of data types for prepared parameters
     * @param array<mixed> $data Array of data for prepared parameters
     * @return bool
     */
    public function preparedQuery(string $query, array $types, array $data): bool
    {
        if (!$this->prepare($query)) {
            return false;
        }

        $result = $this->execute($types, $data);
        $this->disconnectIfAllowed();

        return $result;
    }

    /**
     * Start a transaction
     */
    public function startTransaction(): void
    {
        $this->connect();
        $this->dbobj->exec('BEGIN;');
        $this->transactionOpen = true;
    }

    /**
     * Commit a transaction
     */
    public function commit(): void
    {
        $this->dbobj->exec('COMMIT;');
        $this->transactionOpen = false;
        $this->disconnectIfAllowed();
    }

    /**
     * Rollback a transaction
     */
    public function rollback(): void
    {
        $this->dbobj->exec('ROLLBACK;');
        $this->transactionOpen = false;
        $this->disconnectIfAllowed();
    }

    /**
     * Escape a string on the server
     *
     * @param string $value String to escape
     * @return string
     */
    public function escape(string $value): string
    {
        return SQLite3::escapeString($value);
    }

    /**
     * Returns last error
     *
     * @return string
     */
    public function lastError(): string
    {
        return $this->dbobj->lastErrorMsg();
    }

    /**
     * Get ID of last inserted record
     *
     * @return bool|int
     */
    public function getLastInsertId(): bool|int
    {
        if (!$this->query('SELECT last_insert_rowid()') || !isset($this->result[0]['last_insert_rowid()'])) {
            return false;
        }

        return $this->result[0]['last_insert_rowid()'];
    }

    /**
     * Output debugging information.
     *
     * @param string $data Data to output
     */
    protected function debugPrint($data): void
    {
        if ($this->debugPrint) {
            if (php_sapi_name() === 'cli') {
                echo "DEBUG: [ {$data} ]\n";
            } else {
                echo "<pre>DEBUG: [ {$data} ]</pre>";
            }
        }
        if ($this->debugLog) {
            error_log("DEBUG: [ {$data} ]\n");
        }
    }

    /**
     * Object destructor
     */
    function __destruct()
    {
        $this->disconnect();
    }
}

