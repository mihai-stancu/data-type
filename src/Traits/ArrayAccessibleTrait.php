<?php

/*
 * Copyright (c) 2017 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 */

namespace MS\DataType\Traits;

/**
 * @method bool          has($key)
 * @method mixed         get($key)
 * @method bool          add($value)
 * @method bool          set($key, $value)
 * @method bool          remove($key)
 */
trait ArrayAccessibleTrait
{
    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $offset
     * @param string $value
     *
     * @return bool
     */
    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            return $this->add($value);
        }

        return $this->set($offset, $value);
    }

    /**
     * @param string $offset
     *
     * @return bool
     */
    public function offsetUnset($offset)
    {
        return $this->remove($offset);
    }
}
