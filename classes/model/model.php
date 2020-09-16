<?php
    namespace model;

    use db\interfaces\db_interface;
    use db\sql_builder;

    abstract class model {
        protected $db;
        protected $sql_builder;
        protected $table;
        protected $primary_key = 'id';
        protected $data = [];
        protected $changed = [];

        public function __construct(db_interface $db, sql_builder $sql_builder) {
            $this->db = $db;
            $this->sql_builder = $sql_builder;
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
            return $this->retrieve_where([$this->primary_key => $id]);
        }

        public function retrieve_where($where) {
            $this->data = [];
            $this->changed = [];

            $sql = $this
                ->sql_builder
                ->reset()
                ->select()
                ->from($this->table)
                ->where($where)
                ->limit(1)
                ->sql();

            if (!$this->db->prepared_query($sql['sql'], $sql['types'], $sql['params']) || !isset($this->db->result[0])) {
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
