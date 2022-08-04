<?php
    namespace framework\db;

    class mysql_builder extends sql_builder {
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

                    $types[] = 's';
                    $params[] = $value;
                }

                $sql .= $values.')';
            } elseif ($this->update) {
                $sql .= 'UPDATE `'.$this->table.'` SET ';

                $set = '';
                foreach ($this->set as $field => $value) {
                    $set .= $set === '' ? '`'.$field.'`=?' : ',`'.$field.'`=?';

                    $types[] = 's';
                    $params[] = $value;
                }

                $sql .= $set;
            }

            if ((is_array($this->select) || $this->update) && (is_array($this->where) && count($this->where) > 0)) {
                $where = '';
                foreach($this->where as $field => $value) {
                    $where .= $where === '' ? ' WHERE ' : ' AND ';
                    $where .= '`'.$field.'`=?';

                    $types[] = 's';
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

            if (array_key_exists('mysql_engine', $this->create_table_params)) {
                $sql .= " ENGINE={$this->create_table_params['mysql_engine']}";
            }

            if (array_key_exists('charset', $this->create_table_params)) {
                $sql .= " DEFAULT CHARSET={$this->create_table_params['charset']}";
            }

            return [
                'sql' => $sql,
                'types' => [],
                'params' => []
            ];
        }

        protected function get_field_params($params) {
            $sql = '';

            if (array_key_exists('unsigned', $params) && $params['unsigned'] === true) {
                $sql .= ' UNSIGNED';
            }

            if (array_key_exists('unique', $params) && $params['unique'] === true) {
                $sql .= ' UNIQUE';
            }

            if (array_key_exists('increment', $params) && $params['increment'] === true) {
                $sql .= ' AUTO_INCREMENT';
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
            $size = $params['size'] ?? 11;
            $sql = "`{$name}` INT({$size})";
            $sql .= $this->get_field_params($params);

            return $sql;
        }

        public function field_string($name, $params) {
            $size = $params['size'] ?? 255;
            $sql = "`{$name}` VARCHAR({$size})";
            $sql .= $this->get_field_params($params);

            return $sql;
        }

        public function field_boolean($name, $params) {
            $size = $params['size'] ?? 1;
            $sql = "`{$name}` TINYINT({$size})";
            $sql .= $this->get_field_params($params);

            return $sql;
        }
    }
