<?php
    namespace db;

    class mysql_builder extends sql_builder {
        public function sql() {
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
    }
