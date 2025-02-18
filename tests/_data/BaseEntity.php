<?php
use TeensyPHP\Traits\ArrayAccessImplementation;
use TeensyPHP\Traits\Crud;

class BaseEntity implements \ArrayAccess
{
    use ArrayAccessImplementation,Crud;

    public int $id = 0;
    public string $name = "";
}