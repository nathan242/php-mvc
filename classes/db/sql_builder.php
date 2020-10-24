<?php
    namespace db;

    abstract class sql_builder {
        protected $select;
        protected $table;
        protected $where;
        protected $limit;

        protected $insert;

        protected $update = false;
        protected $set;

        protected $create_table;
        protected $create_table_params;
        protected $create_fields;

        public function select($select = []) {
            $this->select = $select;
            return $this;
        }

        public function insert($insert = []) {
            $this->insert = $insert;
            return $this;
        }

        public function update($update) {
            $this->table = $update;
            $this->update = true;
            return $this;
        }

        public function from($table) {
            $this->table = $table;
            return $this;
        }

        public function into($table) {
            $this->table = $table;
            return $this;
        }

        public function where($where = []) {
            $this->where = $where;
            return $this;
        }

        public function set($set) {
            $this->set = $set;
            return $this;
        }

        public function limit($limit) {
            $this->limit = $limit;
            return $this;
        }

        public function reset() {
            $this->select = null;
            $this->table = null;
            $this->where = null;
            $this->limit = null;
            $this->insert = null;
            $this->update = false;
            $this->set = null;
            $this->create_table = null;
            $this->create_table_params = null;
            $this->create_fields = null;
            return $this;
        }

        public function create($table, $params = []) {
            $this->create_table = $table;
            $this->create_table_params = $params;
            return $this;
        }

        public function field($name, $type, $params = []) {
            $type_method = "field_{$type}";
            $this->create_fields[] = $this->{$type_method}($name, $params);

            return $this;
        }

        abstract public function field_int($name, $params);
        abstract public function field_string($name, $params);
        abstract public function field_boolean($name, $params);
        abstract public function sql();
    }
