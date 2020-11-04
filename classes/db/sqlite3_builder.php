<?php
    namespace db;

    class sqlite3_builder extends sql_builder {
        public function sql() {
            if ($this->create_table !== null) {
                return $this->create_sql();
            }

            $types = [];
            $params = [];
            $sql = '';

            if (is_array($this->select)) {
                $sql .= 'SELECT ';
                if (count($this->select) > 0) {
                    $sql .= '`'.implode('`,`', $this->select).'`';
                } else {
                    $sql .= '*';
                }

                $sql .= ' FROM `'.$this->table.'`';
            } elseif (is_array($this->insert)) {
                $sql .= 'INSERT INTO `'.$this->table.'`';
                $sql .= ' (`'.implode('`,`', array_keys($this->insert)).'`)';
                $sql .= ' VALUES (';

                $values = '';

                foreach ($this->insert as $field => $value) {
                    $values .= $values === '' ? '?' : ',?';

                    $types[] = SQLITE3_TEXT;
                    $params[] = $value;
                }

                $sql .= $values.')';
            } elseif ($this->update) {
                $sql .= 'UPDATE `'.$this->table.'` SET ';

                $set = '';
                foreach ($this->set as $field => $value) {
                    $set .= $set === '' ? '`'.$field.'`=?' : ',`'.$field.'`=?';

                    $types[] = SQLITE3_TEXT;
                    $params[] = $value;
                }

                $sql .= $set;
            }

            if ((is_array($this->select) || $this->update) && (is_array($this->where) && count($this->where) > 0)) {
                $where = '';
                foreach($this->where as $field => $value) {
                    $where .= $where === '' ? ' WHERE ' : ' AND ';
                    $where .= '`'.$field.'`=?';

                    $types[] = SQLITE3_TEXT;
                    $params[] = $value;
                }

                $sql .= $where;
            }

            if (is_array($this->select) && $this->limit !== null) {
                $sql .= " LIMIT {$this->limit}";
            }

            return [
                'sql' => $sql,
                'types' => $types,
                'params' => $params
            ];
        }

        protected function create_sql() {
            $sql = "CREATE TABLE `{$this->create_table}` (";
            $sql .= implode(', ', $this->create_fields);
            $sql .= ')';

            return [
                'sql' => $sql,
                'types' => [],
                'params' => []
            ];
        }

        protected function get_field_params($params) {
            $sql = '';

            if (array_key_exists('unique', $params) && $params['unique'] === true) {
                $sql .= ' UNIQUE';
            }

            if (array_key_exists('primary', $params) && $params['primary'] === true) {
                $sql .= ' PRIMARY KEY';
            }

            if (array_key_exists('required', $params) && $params['required'] === true) {
                $sql .= ' NOT NULL';
            }

            if (array_key_exists('default', $params)) {
                $sql .= " DEFAULT '{$params['default']}'";
            }

            return $sql;
        }

        public function field_int($name, $params) {
            $sql = "`{$name}` INTEGER";
            $sql .= $this->get_field_params($params);

            return $sql;
        }

        public function field_string($name, $params) {
            $sql = "`{$name}` TEXT";
            $sql .= $this->get_field_params($params);

            return $sql;
        }

        public function field_boolean($name, $params) {
            $sql = "`{$name}` INTEGER";
            $sql .= $this->get_field_params($params);

            return $sql;
        }
    }
