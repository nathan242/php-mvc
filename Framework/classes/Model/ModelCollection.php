<?php

namespace Framework\Model;

use ArrayAccess;
use RuntimeException;

/**
 * Model collection
 *
 * @package Framework\Model
 */
class ModelCollection implements ArrayAccess
{
    /** @var array<Model> $items */
    protected $items = [];

    #[\ReturnTypeWillChange]
    /**
     * Set collection item
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
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

    #[\ReturnTypeWillChange]
    /**
     * Check if collection item exists
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    #[\ReturnTypeWillChange]
    /**
     * Remove collection item
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    #[\ReturnTypeWillChange]
    /**
     * Get collection item
     *
     * @param mixed $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
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

