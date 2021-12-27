<?php
    namespace db;

    use mysqli;
    use db\interfaces\db_interface;

    class mdb implements db_interface {
        public $host;
        public $username;
        public $password;
        public $db;
        public $port;
        public $socket;

        public $debug_print = false;
        public $debug_log = false;

        public $keep_connected = true;
        public $result;

        protected $dbobj;
        protected $is_connected = false;
        protected $transaction_open = false;
        protected $qresult;
        protected $stmt;

        /**
         * Construct DB object.
         * 
         * @param array $config DB config
         */
        function __construct($config = []) {
            $this->host = $config['host'] ?? null;
            $this->username = $config['username'] ?? null;
            $this->password = $config['password'] ?? null;
            $this->db = $config['db'] ?? null;
            $this->port = $config['port'] ?? 3306;
            $this->socket = $config['socket'] ?? false;
        }

        /**
         * Connect to the DB server.
         * 
         * @return boolean
         */
        public function connect() {
            if (!$this->is_connected) {
                if ($this->dbobj = new mysqli($this->host, $this->username, $this->password, $this->db, $this->port, $this->socket)) {
                    $this->is_connected = true;
                    $this->transaction_open = false;
                    //TODO: Else, throw error
                } else {
                    return false;
                }
            }
            return true; 
        }

        /**
         * Disconnect from the database server.
         * 
         * @return boolean
         */
        public function disconnect() {
            if ($this->is_connected) {
                $this->dbobj->close();
                $this->is_connected = false;
                $this->transaction_open = false;
            }

            return true;
        }

        /**
         * Disconnect from the DB server if set to not remain connected or a transaction is not open
         */
        private function disconnect_if_allowed() {
            if (!$this->keep_connected && !$this->transaction_open) {
                $this->disconnect(); 
            }
        }

        /**
         * Execute a SQL query and store result in $this->result.
         * 
         * @param string $query SQL query
         * @return boolean
         */
        public function query($query) {
            $this->connect();
            $this->debug_print("QUERY = ".$query);
            if ($this->qresult = $this->dbobj->query($query)) {
                $this->result = $this->query_fetch();
                $this->disconnect_if_allowed();
                return true;
            } else {
                $this->disconnect_if_allowed();
                return false; //mysqli_error()
            }
        }

        /**
         * Fetch query results.
         * 
         * @return array|boolean
         */
        private function query_fetch() {
            $output = array();
            if (isset($this->qresult->num_rows) && $this->qresult->num_rows > 0) {
                $fields = $this->qresult->fetch_fields();
                $field_names = array();
                foreach ($fields as $field){
                    $field_names[] = $field->name;
                }
                while ($row = $this->qresult->fetch_row()) {
                    $table_row = array_combine($field_names, $row);
                    $output[] = $table_row;
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
         * @return boolean
         */
        public function prepare($query) {
            $this->connect();
            $this->debug_print("PREPARE_QUERY = {$query}");
            if (!$this->stmt = $this->dbobj->prepare($query)) {
                $this->disconnect_if_allowed();
                return false;
            }

            return true;
        }

        /**
         * Execute the prepared query and store result in $this->result
         * 
         * @param array $types Array of data types for prepared parameters
         * @param array $data Array of data for prepared parameters
         * @return boolean
         */
        public function execute($types, $data) {
            $this->debug_print('EXECUTE_PREPARED_QUERY | TYPES = '.print_r($types,1).' | DATA = '.print_r($data,1));

            // bind_param
            $bind_params = array();
            $param_type = '';
            $n = count($types);

            for ($i = 0; $i < $n; $i++) {
                $param_type .= $types[$i];
            }

            $bind_params[] = & $param_type;
            for ($i = 0; $i < $n; $i++) {
                $bind_params[] = & $data[$i];
            }

            if (!call_user_func_array(array($this->stmt, 'bind_param'), $bind_params)) {
                $this->disconnect_if_allowed();
                return false;
            }

            if (!$this->stmt->execute()) {
                $this->disconnect_if_allowed();
                return false;
            }

            $this->result = $this->prepared_query_fetch();
            return true;
        }

        /**
         * Prepare and execute an SQL query and store result in $this->result
         * 
         * @param string $query SQL query
         * @param array $types Array of data types for prepared parameters
         * @param array $data Array of data for prepared parameters
         * @return boolean
         */
        public function prepared_query($query, $types, $data) {
            if (!$this->prepare($query)) {
                return false;
            }

            $result = $this->execute($types, $data);
            $this->disconnect_if_allowed();
            return $result;
        }

        /**
         * Fetch prepared query results.
         * 
         * @return array|boolean
         */
        private function prepared_query_fetch() {
            if ($meta = $this->stmt->result_metadata()) {
                $names = array();
                while ($field = $meta->fetch_field()) {
                    $name = $field->name;
                    $names[$name] = null;
                    $result[$field->name] = &$names[$name];
                }
                call_user_func_array(array($this->stmt, 'bind_result'), $result);
                $output = array();
                while ($this->stmt->fetch()) {
                    foreach ($result as $key=>$value) {
                        $result_temp[$key] = $value;
                    }
                    $output[] = $result_temp;
                }
                return $output;
            } else {
                return false;
            }
        }

        /**
         * Start a transaction.
         */
        public function start_transaction() {
            $this->connect();
            $this->dbobj->begin_transaction();
            $this->transaction_open = true;
        }

        /**
         * Commit a transaction.
         */
        public function commit() {
            $this->dbobj->commit();
            $this->transaction_open = false;
            $this->disconnect_if_allowed();
        }

        /**
         * Rollback a transaction.
         */
        public function rollback() {
            $this->dbobj->rollback();
            $this->transaction_open = false;
            $this->disconnect_if_allowed();
        }

        /**
         * Escape a string on the server.
         * 
         * @param string $value String to escape
         * @return string
         */
        public function escape($value) {
            return $this->dbobj->real_escape_string($value);
        }

        /**
         * Returns last error.
         * 
         * @return string
         */
        public function last_error() {
            return $this->dbobj->error;
        }

        /**
         * Get ID of last inserted record
         *
         * @return bool|int
         */
        public function get_last_insert_id() {
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
        private function debug_print($data) {
            if ($this->debug_print) {
                if (php_sapi_name() === 'cli') {
                    echo "DEBUG: [ ".$data." ]\n";
                } else {
                    echo "<pre>DEBUG: [ ".$data." ]</pre>";
                }
            }
            if ($this->debug_log) {
                error_log("DEBUG: [ ".$data." ]\n");
            }
        }

        /**
         * Object destructor.
         */
        function __destruct() {
            $this->disconnect();
        }
}
