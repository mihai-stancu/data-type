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
trait PropertyAccessibleTrait
{
    /**
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        return $this->has($name);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return bool
     */
    public function __set($name, $value)
    {
        return $this->set($name, $value);
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function __unset($name)
    {
        return $this->remove($name);
    }
}
