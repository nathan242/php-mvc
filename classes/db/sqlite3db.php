<?php
    namespace db;

    use SQLite3;
    use db\interfaces\db_interface;

    class sqlite3db implements db_interface {
        public $db_filename;

        public $debug_print = false;
        public $debug_log = false;

        public $keep_connected = true;
        public $result;

        private $dbobj;
        private $is_connected = false;
        private $transaction_open = false;
        private $qresult;
        private $stmt;

        function __construct($config) {
            $this->db_filename = $config['db_filename'] ?? null;
        }

        public function connect() {
            if (!$this->is_connected) {
                if ($this->dbobj = new SQLite3($this->db_filename)) {
                    $this->is_connected = true;
                    $this->transaction_open = false;
                } else {
                    return false;
                }
            }

            return true;
        }

        public function disconnect() {
            if ($this->is_connected) {
                $this->dbobj->close();
                $this->is_connected = false;
                $this->transaction_open = false;
            }

            return true;
        }

        private function disconnect_if_allowed() {
            if (!$this->keep_connected && !$this->transaction_open) {
                $this->disconnect();
            }
        }

        public function query($query) {
            $this->connect();
            $this->debug_print('QUERY = '.$query);

            if ($this->qresult = $this->dbobj->query($query)) {
                $this->result = $this->get_result($this->qresult);
                $this->disconnect_if_allowed();

                return true;
            } else {
                $this->disconnect_if_allowed();
                return false;
            }
        }

        public function prepare($query) {
            $this->connect();
            $this->debug_print("PREPARE_QUERY = {$query}");
            if (!$this->stmt = $this->dbobj->prepare($query)) {
                $this->disconnect_if_allowed();
                return false;
            }

            return true;
        }

        public function execute($types, $data) {
            $this->debug_print('EXECUTE_PREPARED_QUERY | TYPES = '.print_r($types,1).' | DATA = '.print_r($data,1));

            $param_count = count($types);

            for ($i = 0; $i < $param_count; $i++) {
                if (!$this->stmt->bindValue($i+1, $data[$i], $types[$i])) {
                    $this->disconnect_if_allowed();
                    return false;
                }
            }

            if (!$result = $this->stmt->execute()) {
                $this->disconnect_if_allowed();
                return false;
            }

            $this->result = $this->get_result($result);
            return true;
        }

        private function get_result($result) {
            $arr = [];

            if ($result->numColumns() !== 0) {
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    $arr[] = $row;
                }
            }

            return $arr;
        }

        public function prepared_query($query, $types, $data) {
            if (!$this->prepare($query)) {
                return false;
            }

            $result = $this->execute($types, $data);
            $this->disconnect_if_allowed();
            return $result;
        }

        public function start_transaction() {
            $this->connect();
            $this->dbobj->exec('BEGIN;');
            $this->transaction_open = true;
        }

        public function commit() {
            $this->dbobj->exec('COMMIT;');
            $this->transaction_open = false;
            $this->disconnect_if_allowed();
        }

        public function rollback() {
            $this->dbobj->exec('ROLLBACK;');
            $this->transaction_open = false;
            $this->disconnect_if_allowed();
        }

        public function escape($value) {
            return SQLite3::escapeString($value);
        }

        public function last_error() {
            return $this->dbobj->lastErrorMsg();
        }

        public function get_last_insert_id() {
            if (!$this->query('SELECT last_insert_rowid()') || !isset($this->result[0]['last_insert_rowid()'])) {
                return false;
            }

            return $this->result[0]['last_insert_rowid()'];
        }

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

        function __destruct() {
            $this->disconnect();
        }
    }

