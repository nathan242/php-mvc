<?php
    namespace framework\model;

    use framework\db\interfaces\db_interface;
    use framework\db\sql_builder;

    abstract class model {
        protected $db;
        protected $sql_builder;
        protected $model_collection;
        protected $table;
        protected $primary_key = 'id';
        protected $data = [];
        protected $changed = [];

        public function __construct(db_interface $db, sql_builder $sql_builder, model_collection $model_collection) {
            $this->db = $db;
            $this->sql_builder = $sql_builder;
            $this->model_collection = $model_collection;
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

        public function all() {
            return $this->where([]);
        }

        public function where($where) {
            $sql = $this
                ->sql_builder
                ->reset()
                ->select()
                ->from($this->table)
                ->where($where)
                ->sql();

            if (count($where) === 0) {
                if (!$this->db->query($sql['sql'])) {
                    return false;
                }
            } else {
                if (!$this->db->prepared_query($sql['sql'], $sql['types'], $sql['params'])) {
                    return false;
                }
            }

            $collection = clone $this->model_collection;
            foreach ($this->db->result as $record) {
                $model = clone $this;
                $collection[] = $model->set_record($record);
            }

            return $collection;
        }

        public function set_record($data) {
            $this->data = $data;
            $this->changed = [];

            return $this;
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

            $sql = $this
                ->sql_builder
                ->reset()
                ->update($this->table)
                ->set($data)
                ->where([$this->primary_key => $this->data[$this->primary_key]])
                ->sql();

            if (!$this->db->prepared_query($sql['sql'], $sql['types'], $sql['params'])) {
                return false;
            }

            return true;
        }

        public function insert() {
            $sql = $this
                ->sql_builder
                ->reset()
                ->insert($this->data)
                ->into($this->table)
                ->sql();

            if (!$this->db->prepared_query($sql['sql'], $sql['types'], $sql['params'])) {
                return false;
            }

            $insert_id = $this->db->get_last_insert_id();

            if (false === $insert_id) {
                return false;
            }

            return $insert_id;
        }

        public function to_array() {
            return $this->data;
        }
    }
