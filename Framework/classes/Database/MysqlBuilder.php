<?php

namespace Framework\Database;

/**
 * MySQL SQL builder
 *
 * @package Framework\Database
 */
class MysqlBuilder extends SqlBuilder
{
    /**
     * Output SQL, datatypes and parameters
     *
     * @return array<string, mixed>
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

                $types[] = 's';
                $params[] = $value;
            }

            $sql .= $values . ')';
        } elseif ($this->update) {
            $sql .= 'UPDATE `' . $this->table . '` SET ';

            $set = '';
            foreach ($this->set as $field => $value) {
                $set .= $set === '' ? '`' . $field . '`=?' : ',`' . $field . '`=?';

                $types[] = 's';
                $params[] = $value;
            }

            $sql .= $set;
        }

        if ((is_array($this->select) || $this->update) && (is_array($this->where) && count($this->where) > 0)) {
            $where = '';
            foreach ($this->where as $field => $value) {
                $where .= $where === '' ? ' WHERE ' : ' AND ';
                $where .= '`' . $field . '`=?';

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

    /**
     * Output CREATE TABLE SQL
     *
     * @return array<string, mixed>
     */
    protected function createSql(): array
    {
        $sql = "CREATE TABLE `{$this->createTable}` (";
        $sql .= implode(', ', $this->createFields);
        $sql .= ')';

        if (array_key_exists('mysql_engine', $this->createTableParams)) {
            $sql .= " ENGINE={$this->createTableParams['mysql_engine']}";
        }

        if (array_key_exists('charset', $this->createTableParams)) {
            $sql .= " DEFAULT CHARSET={$this->createTableParams['charset']}";
        }

        return [
            'sql' => $sql,
            'types' => [],
            'params' => []
        ];
    }

    /**
     * Get field parameters SQL string
     *
     * @param array<string, mixed> $params
     * @return string
     */
    protected function getFieldParams(array $params): string
    {
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

    /**
     * Get INT field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function fieldInt(string $name, array $params): string
    {
        $size = $params['size'] ?? 11;
        $sql = "`{$name}` INT({$size})";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get STRING field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function fieldString(string $name, array $params): string
    {
        $size = $params['size'] ?? 255;
        $sql = "`{$name}` VARCHAR({$size})";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get BOOL field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function fieldBoolean(string $name, array $params): string
    {
        $size = $params['size'] ?? 1;
        $sql = "`{$name}` TINYINT({$size})";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get DATE field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function fieldDate(string $name, array $params): string
    {
        $sql = "`{$name}` DATE";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }

    /**
     * Get DECIMAL field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    public function fieldDecimal(string $name, array $params): string
    {
        $size = $params['size'] ?? '10,2';
        $sql = "`{$name}` DECIMAL({$size})";
        $sql .= $this->getFieldParams($params);

        return $sql;
    }
}
