<?php

namespace TeensyPhp;

use ArrayAccess;

abstract class DataMapper implements ArrayAccess
{
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $value) {
            if ($key[0] === ':') {
                $cleankey = substr($key, 1);
                $this->{$cleankey} = $value;
                continue;
            }

            $this->{$key} = $value;
        }
    }

    public function offsetExists($offset): bool
    {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset)
    {
        if (!property_exists($this, $offset)) {
            return null;
        }

        return $this->{$offset};
    }

    public function offsetSet($offset, $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset($offset): void
    {
        $this->{$offset} = null;
    }
}
