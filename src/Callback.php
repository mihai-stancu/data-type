<?php

/*
 * Copyright (c) 2017 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 */

namespace MS\DataType;

class Callback
{
    /** @var array|string */
    protected $definition;

    /**
     * @param array|string $definition
     */
    public function __construct($definition)
    {
        if (is_string($definition) and strpos($definition, '::') !== false) {
            $definition = explode('::', $definition);
        }

        $this->definition = $definition;
    }

    /**
     * @param mixed $value
     *
     * @return callable
     */
    public function isCallable($value)
    {
        if (!is_callable($this->toCallable($value))) {
            $message = sprintf('Invalid callback received: "%s".', $this->toString($value));
            throw new \InvalidArgumentException($message);
        }
    }

    /**
     * @param mixed $value
     *
     * @return callable
     */
    public function toCallable($value)
    {
        $definition = $this->definition;

        if (is_callable($definition)) {
            return $definition;
        }

        if (is_array($definition) and in_array($definition[0], ['this', 'self'], true) and is_object($value)) {
            $definition[0] = $value;
        }

        if (is_callable($definition)) {
            return $definition;
        }
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    protected function toString($value)
    {
        $definition = $this->definition;

        if (is_string($definition)) {
            return $definition;
        }

        if (in_array($definition[0], ['this', 'self'], true) and is_object($value)) {
            $definition[0] = $value;
        }

        if (is_object($definition[0])) {
            $definition[0] = get_class($definition[0]);
        }

        return $definition[0].'::'.$definition[1];
    }
}
