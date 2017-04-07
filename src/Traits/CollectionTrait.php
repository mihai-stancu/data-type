<?php

/*
 * Copyright (c) 2017 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 */

namespace MS\DataType\Traits;

use MS\DataType\Callback;

/**
 * A more flexible replacement for array_map / array_walk / array_reduce and any other callback-based processing.
 */
trait CollectionTrait
{
    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this);
    }

    /**
     * @param array|string $callback
     * @param array        $parameters
     * @param string       $output
     *
     * @return array|\ArrayAccess|static
     */
    public function walk($callback, $output = null)
    {
        if ($output === null) {
            $output = clone $this;
            $output->exchangeArray([]);
        } elseif (is_string($output) and class_exists($output)) {
            $output = new $output();
        }

        foreach ($this as $key => $value) {
            $output[$key] = $callback($value, $key);
        }

        return $output;
    }

    /**
     * @param array|string $callback
     * @param array        $parameters
     *
     * @return static
     */
    public function filter($callback = null)
    {
        $callback = $callback ?? [$this, 'valueIsEmpty'];
        $output = clone $this;
        $output->exchangeArray([]);

        foreach ($this as $key => $value) {
            if ($callback($value, $key)) {
                $output[$key] = $value;
            }
        }

        return $output;
    }

    /**
     * @param array|\ArrayAccess[] $collections
     *
     * @return static
     */
    public function merge(...$collections)
    {
        array_unshift($collections, $this);
        foreach ($collections as &$collection) {
            $collection = (array) $collection;
        }
        unset($collection);

        $merged = array_merge(...$collections);

        $collections = clone $this;
        $collections->exchangeArray($merged);

        return $collections;
    }

    /**
     * @param $value
     *
     * @return bool
     */
    public function valueIsEmpty($value)
    {
        return !empty($value);
    }

    /**
     * @param array|string[] $keys
     *
     * @return static
     */
    public function combine(array $keys)
    {
        $array = $this->getArrayCopy();
        $array = array_combine($keys, $array);

        return new static($array);
    }

    /**
     * @return array
     */
    public function values()
    {
        return array_values($this->getArrayCopy());
    }

    /**
     * @param string $name
     *
     * @return array
     */
    public function column($name)
    {
        $values = [];
        foreach ($this as $i => $element) {
            $values[$i] = $element->$name;
        }

        return $values;
    }
}
