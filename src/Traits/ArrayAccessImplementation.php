<?php

namespace TeensyPHP\Traits;

trait ArrayAccessImplementation
{
    public function offsetExists(mixed $offset): bool
    {
        // check if the property exists
        return isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
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