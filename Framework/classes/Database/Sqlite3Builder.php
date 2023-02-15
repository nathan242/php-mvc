<?php

namespace Framework\Database;

/**
 * SQlite3 SQL builder
 *
 * @package Framework\Database
 */
class Sqlite3Builder extends SqlBuilder
{
    /**
     * Output SQL, datatypes and parameters
     *
     * @return array
     */
    public function sql(): array
    {
        if ($this->createTable !== null) {
            return $this->createSql();
        }

        $types = [];
        $params = [];
        $sql = '';

        if (is_array($this->select)) {
            $sql .= 'SELECT ';
            if (count($this->select) > 0) {
                $sql .= '`' . implode('`,`', $this->select) . '`';
            } else {
                $sql .= '*';
            }

            $sql .= ' FROM `' . $this->table . '`';
        } elseif (is_array($this->insert)) {
            $sql .= 'INSERT INTO `' . $this->table . '`';
            $sql .= ' (`' . implode('`,`', array_keys($this->insert)) . '`)';
            $sql .= ' VALUES (';

            $values = '';

            foreach ($this->insert as $field => $value) {
                $values .= $values === '' ? '?' : ',?';

                $types[] = SQLITE3_TEXT;
                $params[] = $value;
            }

            $sql .= $values . ')';
        } elseif ($this->update) {
            $sql .= 'UPDATE `' . $this->table . '` SET ';

            $set = '';
            foreach ($this->set as $field => $value) {
                $set .= $set === '' ? '`' . $field . '`=?' : ',`' . $field . '`=?';

                $types[] = SQLITE3_TEXT;
                $params[] = $value;
            }

            $sql .= $set;
        }

        if ((is_array($this->select) || $this->update) && (is_array($this->where) && count($this->where) > 0)) {
            $where = '';
            foreach ($this->where as $field => $value) {
                $where .= $where === '' ? ' WHERE ' : ' AND ';
                $where .= '`' . $field . '`=?';

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

    /**
     * Output CREATE TABLE SQL
     *
     * @return array
     */
    protected function createSql(): array
    {
        $sql = "CREATE TABLE `{$this->createTable}` (";
        $sql .= implode(', ', $this->createFields);
        $sql .= ')';

        return [
            'sql' => $sql,
            'types' => [],
            'params' => []
        ];
    }

    /**
     * Get field parameters SQL string
     *
     * @param array $params
     * @return string
     */
    protected function getFieldParams(array $params): string
    {
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

    /**
     * Get INT field SQL
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function fieldInt(string $name, array $params): string
    {
        $sql = "`{$name}` INTEGER";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get STRING field SQL
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function fieldString(string $name, array $params): string
    {
        $sql = "`{$name}` TEXT";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get BOOL field SQL
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function fieldBoolean(string $name, array $params): string
    {
        $sql = "`{$name}` INTEGER";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get DATE field SQL
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function fieldDate(string $name, array $params): string
    {
        $sql = "`{$name}` TEXT";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get DECIMAL field SQL
     *
     * @param string $name
     * @param array $params
     * @return string
     */
    public function fieldDecimal(string $name, array $params): string
    {
        $sql = "`{$name}` NUMERIC";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }
}
