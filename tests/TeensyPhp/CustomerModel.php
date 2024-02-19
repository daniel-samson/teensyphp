<?php

namespace TeensyPhp\Tests;

use TeensyPhp\DataMapper;

class CustomerModel extends DataMapper
{
    public int $id;
    public string $name;
    public string $email;
}
