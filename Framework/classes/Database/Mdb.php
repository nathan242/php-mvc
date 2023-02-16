<?php

namespace Framework\Database;

use mysqli;
use mysqli_result;
use Framework\Database\Interfaces\DatabaseInterface;

/**
 * Mysql database connection
 *
 * @package Framework\Database
 */
class Mdb implements DatabaseInterface
{
    /** @var string|null $host */
    public $host;

    /** @var string|null $username */
    public $username;

    /** @var string|null $password */
    public $password;

    /** @var string|null $db */
    public $db;

    /** @var int|null $port */
    public $port;

    /** @var string|bool $socket */
    public $socket;

    /** @var bool $debugPrint Enable to print debug messages */
    public $debugPrint = false;

    /** @var bool $debugLog Enable to log debug messages */
    public $debugLog = false;

    /** @var bool $keepConnected Enable to keep connection open after query */
    public $keepConnected = true;

    /** @var mixed|null $result Query result */
    public $result;

    /** @var mysqli $dbobj MySql database object */
    protected $dbobj;

    /** @var bool $isConnected Connection status */
    protected $isConnected = false;

    /** @var bool $transactionOpen Transaction status */
    protected $transactionOpen = false;

    /** @var mysqli_result $qResult Query result */
    protected $qResult;

    /** @var mixed|null $stmt Prepared statement */
    protected $stmt;

    /**
     * Construct DB object.
     *
     * @param array $config DB config
     */
    function __construct(array $config = [])
    {
        $this->host = $config['host'] ?? null;
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
        $this->db = $config['db'] ?? null;
        $this->port = $config['port'] ?? 3306;
        $this->socket = $config['socket'] ?? false;
    }

    /**
     * Connect to the DB server.
     */
    public function connect()
    {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        if (!$this->isConnected) {
            $this->dbobj = new mysqli($this->host, $this->username, $this->password, $this->db, $this->port, $this->socket);
            $this->isConnected = true;
            $this->transactionOpen = false;
        }
    }

    /**
     * Disconnect from the database server.
     */
    public function disconnect()
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
    private function disconnectIfAllowed()
    {
        if (!$this->keepConnected && !$this->transactionOpen) {
            $this->disconnect();
        }
    }

    /**
     * Execute a SQL query and store result in $this->result
     *
     * @param string $query SQL query
     * @return bool
     */
    public function query(string $query): bool
    {
        $this->connect();
        $this->debugPrint("QUERY = {$query}");

        if ($this->qResult = $this->dbobj->query($query)) {
            $this->result = $this->queryFetch();
            $this->disconnectIfAllowed();
            return true;
        } else {
            $this->disconnectIfAllowed();
            return false; //TODO: mysqli_error()
        }
    }

    /**
     * Fetch query results
     *
     * @return array|boolean
     */
    private function queryFetch()
    {
        $output = [];
        if (isset($this->qResult->num_rows) && $this->qResult->num_rows > 0) {
            $fields = $this->qResult->fetch_fields();
            $fieldNames = array();

            foreach ($fields as $field) {
                $fieldNames[] = $field->name;
            }

            while ($row = $this->qResult->fetch_row()) {
                $tableRow = array_combine($fieldNames, $row);
                $output[] = $tableRow;
            }

            return $output;
        } else {
            return false;
        }
    }

    /**
     * Prepare an SQL query
     *
     * @param string $query SQL query
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
     * @param array $types Array of data types for prepared parameters
     * @param array $data Array of data for prepared parameters
     * @return bool
     */
    public function execute(array $types, array $data): bool
    {
        $this->debugPrint('EXECUTE_PREPARED_QUERY | TYPES = ' . print_r($types, true) . ' | DATA = ' . print_r($data, true));

        $bindParams = [];
        $paramType = '';
        $n = count($types);

        for ($i = 0; $i < $n; $i++) {
            $paramType .= $types[$i];
        }

        $bindParams[] = &$paramType;
        for ($i = 0; $i < $n; $i++) {
            $bindParams[] = &$data[$i];
        }

        if (!call_user_func_array([$this->stmt, 'bind_param'], $bindParams)) {
            $this->disconnectIfAllowed();
            return false;
        }

        if (!$this->stmt->execute()) {
            $this->disconnectIfAllowed();
            return false;
        }

        $this->result = $this->preparedQueryFetch();
        return true;
    }

    /**
     * Prepare and execute an SQL query and store result in $this->result
     *
     * @param string $query SQL query
     * @param array $types Array of data types for prepared parameters
     * @param array $data Array of data for prepared parameters
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
     * Fetch prepared query results.
     *
     * @return array|bool
     */
    private function preparedQueryFetch()
    {
        if ($meta = $this->stmt->result_metadata()) {
            $names = [];
            $result = [];
            while ($field = $meta->fetch_field()) {
                $name = $field->name;
                $names[$name] = null;
                $result[$field->name] = &$names[$name];
            }

            call_user_func_array(array($this->stmt, 'bind_result'), $result);

            $output = [];
            while ($this->stmt->fetch()) {
                $resultTemp = [];
                foreach ($result as $key => $value) {
                    $resultTemp[$key] = $value;
                }
                $output[] = $resultTemp;
            }

            return $output;
        } else {
            return false;
        }
    }

    /**
     * Start a transaction.
     */
    public function startTransaction()
    {
        $this->connect();
        $this->dbobj->begin_transaction();
        $this->transactionOpen = true;
    }

    /**
     * Commit a transaction.
     */
    public function commit()
    {
        $this->dbobj->commit();
        $this->transactionOpen = false;
        $this->disconnectIfAllowed();
    }

    /**
     * Rollback a transaction.
     */
    public function rollback()
    {
        $this->dbobj->rollback();
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
        return $this->dbobj->real_escape_string($value);
    }

    /**
     * Returns last error
     *
     * @return string
     */
    public function lastError(): string
    {
        return $this->dbobj->error;
    }

    /**
     * Get ID of last inserted record
     *
     * @return bool|int
     */
    public function getLastInsertId()
    {
        if (!$this->query('SELECT LAST_INSERT_ID()') || !isset($this->result[0]['LAST_INSERT_ID()'])) {
            return false;
        }

        return $this->result[0]['LAST_INSERT_ID()'];
    }

    /**
     * Output debugging information.
     *
     * @param string $data Data to output
     */
    private function debugPrint(string $data)
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
