<?php
    namespace db\interfaces;

    interface db_interface {
        public function connect();
        public function disconnect();
        public function query($query);
        public function prepare($query);
        public function execute($types, $data);
        public function prepared_query($query, $types, $data);
        public function start_transaction();
        public function commit();
        public function rollback();
        public function escape($value);
        public function last_error();
    }

