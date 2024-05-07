<?php

namespace Framework\Model;

use ArrayAccess;
use RuntimeException;

/**
 * Model collection
 *
 * @package Framework\Model
 * @implements ArrayAccess<int, Model>
 */
class ModelCollection implements ArrayAccess
{
    /** @var array<Model> $items */
    protected $items = [];

    /**
     * Set collection item
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if (!$value instanceof Model) {
            throw new RuntimeException('Attempting to add non model to model collection');
        }

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Check if collection item exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->items[$offset]);
    }

    /**
     * Remove collection item
     *
     * @param mixed $offset
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->items[$offset]);
    }

    /**
     * Get collection item
     *
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items[$offset] ?? null;
    }

    /**
     * Get all collection data as an array
     *
     * @return array<int, array<string, mixed>>
     */
    public function toArray(): array
    {
        $arr = [];
        foreach ($this->items as $model) {
            $arr[] = $model->toArray();
        }

        return $arr;
    }
}

