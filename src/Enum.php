<?php

/*
 * Copyright (c) 2017 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 */

namespace MS\DataType;

class Enum implements \Serializable, \JsonSerializable
{
    public function __construct()
    {
        call_user_func_array([$this, 'set'], func_get_args());
    }

    /**
     * @var array|string[] list of allowed values populated via reflection
     */
    protected static $values = [];

    /**
     * @return array|string[] list of allowed values
     */
    public static function getValues()
    {
        if (empty(static::$values[static::class])) {
            $reflection = new \ReflectionClass(static::class);

            $position = 0;
            $constants = $reflection->getConstants();
            foreach ($constants as $name => $value) {
                list($value, $index) = static::processValue($name, $value, $position);
                static::$values[static::class][$index] = $value;
                ++$position;
            }

            if (!isset(static::$values[static::class][0])) {
                static::$values[static::class][0] = '';
            }

            ksort(static::$values[static::class]);
        }

        return static::$values[static::class];
    }

    /**
     * @param string     $name
     * @param string|int $value
     * @param int        $position
     *
     * @return array
     */
    protected static function processValue($name, $value, $position)
    {
        if (is_int($value)) {
            return [trim($name, '_'), $value];
        }

        return [$value, $position + 1];
    }

    /**
     * @var string
     */
    protected $value;

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->get();
    }

    /**
     * @param string $value
     */
    public function setValue($value)
    {
        $this->set($value);
    }

    /**
     * @param bool $asInteger
     *
     * @return string|int
     */
    public function get($asInteger = false)
    {
        if ($asInteger) {
            return array_search($this->value, static::$values[static::class], true);
        }

        return $this->value;
    }

    /**
     * @param string|int $value
     *
     * @throws \InvalidArgumentException
     */
    public function set($value = null)
    {
        if (!array_key_exists($value, static::getValues()) and !in_array($value, static::getValues(), true)) {
            throw new \InvalidArgumentException(
                vsprintf(
                    'Value "%1$s" is not in list of allowed values: "%2$s".',
                    [
                        $value,
                        implode('", "', static::getValues()),
                    ]
                )
            );
        }

        if (is_int($value)) {
            $this->value = static::$values[static::class][$value];
        } else {
            $this->value = $value;
        }
    }

    /** Integrating with PHP behaviors */

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->value;
    }

    /** Implementing Serializable */

    /**
     * @return string
     */
    public function serialize()
    {
        return serialize($this->get());
    }

    /**
     * @return string
     * @param  mixed  $data
     */
    public function unserialize($data)
    {
        $this->set(unserialize($data));
    }

    /** Implementing JsonSerializable */

    /**
     * @return string|int
     */
    public function jsonSerialize()
    {
        return $this->get();
    }
}
