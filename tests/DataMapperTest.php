<?php

use PHPUnit\Framework\TestCase;
use TeensyPhp\Tests\CustomerModel;

class DataMapperTest extends TestCase
{
    public function test_data_mapper()
    {
        $actual = new CustomerModel([
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'email@example.com'
        ]);

        $this->assertEquals(1, $actual->id);
        $this->assertEquals('John Doe', $actual->name);
        $this->assertEquals('email@example.com', $actual->email);
    }
}
