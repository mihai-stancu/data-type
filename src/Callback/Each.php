<?php

/*
 * Copyright (c) 2017 Mihai Stancu <stancu.t.mihai@gmail.com>
 *
 * This source file is subject to the license that is bundled with this source
 */

namespace MS\DataType\Callback;

class Each
{
    /** @var string */
    protected $method;

    /**
     * @param array|string $definition
     */
    public function __construct($method)
    {
        $this->method = $method;
    }

    /**
     * @param mixed    $value
     * @param string   $key
     *
     * @return array
     */
    public function __invoke($value, $key)
    {
        return call_user_func([$value, $this->method]);
    }
}
