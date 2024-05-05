<?php

namespace Framework\Database;

/**
 * Base SQL builder class
 *
 * @package Framework\Database
 */
abstract class SqlBuilder
{
    /** @var array<string>|null $select */
    protected $select;

    /** @var string|null $table */
    protected $table;

    /** @var array<string, mixed>|null $where */
    protected $where;

    /** @var int|null limit */
    protected $limit;

    /** @var array<string, mixed>|null $insert */
    protected $insert;

    /** @var bool $update */
    protected $update = false;

    /** @var array<string, mixed>|null $set */
    protected $set;

    /** @var string|null $createTable */
    protected $createTable;

    /** @var array<string, mixed>|null $createTableParams */
    protected $createTableParams;

    /** @var array<string>|null $createFields */
    protected $createFields;

    /**
     * Add SELECT fields
     *
     * @param array<string> $select
     * @return self
     */
    public function select($select = []): self
    {
        $this->select = $select;
        return $this;
    }

    /**
     * Add INSERT fields
     *
     * @param array<string, mixed> $insert
     * @return self
     */
    public function insert($insert = []): self
    {
        $this->insert = $insert;
        return $this;
    }

    /**
     * Set table to update
     *
     * @param string $update
     * @return self
     */
    public function update(string $update): self
    {
        $this->table = $update;
        $this->update = true;
        return $this;
    }

    /**
     * Set table to select from
     *
     * @param string $table
     * @return self
     */
    public function from(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set table to insert into
     *
     * @param string $table
     * @return self
     */
    public function into(string $table): self
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set WHERE parameters
     *
     * @param array<string, mixed> $where
     * @return self
     */
    public function where(array $where = []): self
    {
        $this->where = $where;
        return $this;
    }

    /**
     * Set SET parameters
     *
     * @param array<string, mixed> $set
     * @return self
     */
    public function set(array $set): self
    {
        $this->set = $set;
        return $this;
    }

    /**
     * Set query limit
     *
     * @param int $limit
     * @return self
     */
    public function limit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Reset query state
     *
     * @return self
     */
    public function reset(): self
    {
        $this->select = null;
        $this->table = null;
        $this->where = null;
        $this->limit = null;
        $this->insert = null;
        $this->update = false;
        $this->set = null;
        $this->createTable = null;
        $this->createTableParams = null;
        $this->createFields = null;

        return $this;
    }

    /**
     * Create table
     *
     * @param string $table
     * @param array<string, mixed> $params
     * @return self
     */
    public function create(string $table, array $params = []): self
    {
        $this->createTable = $table;
        $this->createTableParams = $params;
        return $this;
    }

    /**
     * Add field to table
     *
     * @param string $name
     * @param string $type
     * @param array<string, mixed> $params
     * @return self
     */
    public function field(string $name, string $type, array $params = []): self
    {
        $typeMethod = 'field'.ucfirst($type);
        $this->createFields[] = $this->{$typeMethod}($name, $params);

        return $this;
    }

    /**
     * Get INT field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    abstract public function fieldInt(string $name, array $params): string;

    /**
     * Get STRING field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    abstract public function fieldString(string $name, array $params): string;

    /**
     * Get BOOL field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    abstract public function fieldBoolean(string $name, array $params): string;

    /**
     * Get DATE field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    abstract public function fieldDate(string $name, array $params): string;

    /**
     * Get DECIMAL field SQL
     *
     * @param string $name
     * @param array<string, mixed> $params
     * @return string
     */
    abstract public function fieldDecimal(string $name, array $params): string;

    /**
     * Output SQL, datatypes and parameters
     *
     * @return array<string, mixed>
     */
    abstract public function sql(): array;
}
