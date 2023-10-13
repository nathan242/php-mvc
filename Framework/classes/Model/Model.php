<?php

namespace Framework\Model;

use Framework\Database\Interfaces\DatabaseInterface;
use Framework\Database\SqlBuilder;

/**
 * Base model class
 *
 * @package Framework\Model
 */
abstract class Model
{
    /** @var DatabaseInterface $db */
    protected $db;

    /** @var SqlBuilder $sqlBuilder */
    protected $sqlBuilder;

    /** @var ModelCollection $modelCollection */
    protected $modelCollection;

    /** @var string $table */
    protected $table;

    /** @var string $primaryKey */
    protected $primaryKey = 'id';

    /** @var array<string, mixed> $data */
    protected $data = [];

    /** @var array<string> $changed */
    protected $changed = [];

    /**
     * Model constructor
     *
     * @param DatabaseInterface $db
     * @param SqlBuilder $sqlBuilder
     * @param ModelCollection $modelCollection
     */
    public function __construct(DatabaseInterface $db, SqlBuilder $sqlBuilder, ModelCollection $modelCollection)
    {
        $this->db = $db;
        $this->sqlBuilder = $sqlBuilder;
        $this->modelCollection = $modelCollection;
    }

    /**
     * Check field is set
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->data);
    }

    /**
     * Get field value
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    /**
     * Set field value
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
        if (!in_array($name, $this->changed)) {
            $this->changed[] = $name;
        }
    }

    /**
     * Get database
     *
     * @return DatabaseInterface
     */
    public function getDatabase(): DatabaseInterface
    {
        return $this->db;
    }

    /**
     * Get SQL builder
     *
     * @return SqlBuilder
     */
    public function getSqlBuilder(): SqlBuilder
    {
        return $this->sqlBuilder;
    }

    /**
     * Get table name
     *
     * @return string
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Get primary key
     *
     * @return string
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }

    /**
     * Get array of changed records
     *
     * @return array<string>
     */
    public function getChanged(): array
    {
        return $this->changed;
    }

    /**
     * Get all records as a collection
     *
     * @return ModelCollection|false
     */
    public function all()
    {
        return $this->where([]);
    }

    /**
     * Get records matching criteria as a collection
     *
     * @param array<string, mixed> $where
     * @return ModelCollection|false
     */
    public function where(array $where)
    {
        $sql = $this
            ->sqlBuilder
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
            if (!$this->db->preparedQuery($sql['sql'], $sql['types'], $sql['params'])) {
                return false;
            }
        }

        $collection = clone $this->modelCollection;
        foreach ($this->db->result as $record) {
            $model = clone $this;
            $collection[] = $model->setRecord($record);
        }

        return $collection;
    }

    /**
     * Load data into model
     *
     * @param array<string, mixed> $data
     * @return $this
     */
    public function setRecord(array $data): self
    {
        $this->data = $data;
        $this->changed = [];

        return $this;
    }

    /**
     * Get record with primary key
     *
     * @param mixed $id
     * @return bool
     */
    public function retrieve($id): bool
    {
        return $this->retrieveWhere([$this->primaryKey => $id]);
    }

    /**
     * Populate this model with data matching criteria
     *
     * @param array<string, mixed> $where
     * @return bool
     */
    public function retrieveWhere(array $where): bool
    {
        $this->data = [];
        $this->changed = [];

        $sql = $this
            ->sqlBuilder
            ->reset()
            ->select()
            ->from($this->table)
            ->where($where)
            ->limit(1)
            ->sql();

        if (!$this->db->preparedQuery($sql['sql'], $sql['types'], $sql['params']) || !isset($this->db->result[0])) {
            return false;
        }

        $this->data = $this->db->result[0];

        return true;
    }

    /**
     * Update record with current model data
     *
     * @return bool
     */
    public function update(): bool
    {
        $data = array_intersect_key($this->data, array_flip($this->changed));

        $sql = $this
            ->sqlBuilder
            ->reset()
            ->update($this->table)
            ->set($data)
            ->where([$this->primaryKey => $this->data[$this->primaryKey]])
            ->sql();

        if (!$this->db->preparedQuery($sql['sql'], $sql['types'], $sql['params'])) {
            return false;
        }

        return true;
    }

    /**
     * Create new record with model data
     *
     * @return int|bool
     */
    public function insert()
    {
        $sql = $this
            ->sqlBuilder
            ->reset()
            ->insert($this->data)
            ->into($this->table)
            ->sql();

        if (!$this->db->preparedQuery($sql['sql'], $sql['types'], $sql['params'])) {
            return false;
        }

        $insertId = $this->db->getLastInsertId();

        if (false === $insertId) {
            return false;
        }

        return $insertId;
    }

    /**
     * Return model data as an array
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->data;
    }
}
