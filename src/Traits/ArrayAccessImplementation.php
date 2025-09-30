<?php

namespace TeensyPHP\Traits;

/**
 * ArrayAccess implementation
 */
trait ArrayAccessImplementation
{
    /**
     * Check if the property exists
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        $properties = get_object_vars($this);
        // check if the property exists
        return array_key_exists($offset, $properties);
    }

    /**
     * Get the property value
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        // check if the property exists
        if (!property_exists($this, $offset)) {
            return null;
        }

        // get property from class
        return $this->{$offset};
    }

    /**
     * Set the property value
     * @param mixed $offset
     * @param mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    /**
     * Unset the property
     * @param mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    /**
     * Convert the object to an array
     * @return array
     */
    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            $array[$key] = $value;
        }
        return $array;
    }
}