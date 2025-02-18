<?php

namespace TeensyPHP\Traits;

trait ArrayAccessImplementation
{
    public function offsetExists(mixed $offset): bool
    {
        $properties = get_object_vars($this);
        // check if the property exists
        return array_key_exists($offset, $properties);
    }

    public function offsetGet(mixed $offset): mixed
    {
        // check if the property exists
        if (!property_exists($this, $offset)) {
            return null;
        }

        // get property from class
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
}