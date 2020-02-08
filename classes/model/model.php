<?php
    namespace model;

    abstract class model {
        private $db;
        protected $table;
        protected $primary_key = 'id';
        protected $data = [];
        protected $changed = [];

        public function __construct($db) {
            $this->db = $db;
        }

        public function __isset($name) {
            return array_key_exists($name, $this->data);
        }

        public function __get($name) {
            return $this->data[$name] ?? null;
        }

        public function __set($name, $value) {
            $this->data[$name] = $value;
            if (!array_key_exists($name, $this->changed)) {
                $this->changed[] = $name;
            }
        }

        public function retrieve($id) {
            return $this->retrieveWhere([$this->primary_key => $id]);
        }

        public function retrieveWhere($where) {
            $this->data = [];
            $this->changed = [];

            if (!is_array($where)) {
                $where = [$this->primary_key => $where];
            }

            $fields = [];
            $types = [];
            $values = [];
            foreach ($where as $key => $value) {
                $fields[] = "`{$key}`=?";
                $types[] = 's';
                $values[] = $value;
            }

            if (!$this->db->prepared_query("SELECT * FROM `{$this->table}` WHERE ".implode(' AND  ', $fields)." LIMIT 1", $types, $values) || !isset($this->db->result[0])) {
                return false;
            }

            $this->data = $this->db->result[0];

            return true;
        }

        public function update() {
            $data = array_intersect_key($this->data, array_flip($this->changed));

            $fields = [];
            $types = [];
            $values = [];
            foreach ($data as $key => $value) {
                $fields[] = "`{$key}`=?";
                $types[] = 's';
                $values[] = $value;
            }

            $types[] = 's';
            $values[] = $this->data[$this->primary_key];

            if (!$this->db->prepared_query("UPDATE `{$this->table}` SET ".implode(', ', $fields)." WHERE {$this->primary_key}=?", $types, $values)) {
                return false;
            }

            return true;
        }

        public function insert() {
            $fields = [];
            $placeholders = [];
            $types = [];
            $values = [];
            foreach ($this->data as $key => $value) {
                $fields[] = $key;
                $placeholders[] = '?';
                $types[] = 's';
                $values[] = $value;
            }

            if (!$this->db->prepared_query("INSERT INTO `{$this->table}` (`".implode('`, `', $fields)."`) VALUES (".implode(', ', $placeholders).")", $types, $values)) {
                return false;
            }

            if (!$this->db->query('SELECT LAST_INSERT_ID()') || !isset($this->db->result[0]['LAST_INSERT_ID()'])) {
                return false;
            }

            return $this->db->result[0]['LAST_INSERT_ID()'];
        }
    }
