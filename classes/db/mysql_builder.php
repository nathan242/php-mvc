<?php
    namespace db;

    class mysql_builder extends sql_builder {
        public function sql() {
            $types = [];
            $params = [];
            $sql = 'SELECT ';

            if (is_array($this->select) && count($this->select) > 0) {
                $sql .= '`'.implode('`,`', $this->select).'`';
            } else {
                $sql .= '*';
            }

            $sql .= ' FROM `'.$this->from.'`';

            if (is_array($this->where) && count($this->where) > 0) {
                $where = '';
                foreach($this->where as $field => $value) {
                    $where .= $where === '' ? ' WHERE ' : ' AND ';
                    $where .= '`'.$field.'` = ?';

                    $types[] = 's';
                    $params[] = $value;
                }

                $sql .= $where;
            }

            return [
                $sql,
                $types,
                $params
            ];
        }
    }
