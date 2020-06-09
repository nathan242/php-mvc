<?php
    namespace db;

    abstract class sql_builder {
        protected $select = [];
        protected $from;
        protected $where = [];

        public function select($select = []) {
            $this->select = $select;
            return $this;
        }

        public function from($table) {
            $this->from = $table;
            return $this;
        }

        public function where($where = []) {
            $this->where = $where;
            return $this;
        }

        public function reset() {
            $this->select = [];
            $this->from = null;
            $this->where = [];
        }

        abstract public function sql();
    }
