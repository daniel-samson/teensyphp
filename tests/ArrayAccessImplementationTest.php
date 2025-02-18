<?php

require_once __DIR__ . "/_data/BaseEntity.php";

use PHPUnit\Framework\TestCase;

class ArrayAccessImplementationTest extends TestCase
{
    public function test_offsetExists()
    {
        $entity = new BaseEntity();
        $this->assertFalse($entity->offsetExists("dob"));
        $this->assertTrue($entity->offsetExists("id"));
    }

    public function test_offsetGet()
    {
        $entity = new BaseEntity();
        $this->assertNull($entity->offsetGet("dob"));
        $this->assertEquals(0, $entity->offsetGet("id"));
    }

    public function test_offsetSet()
    {
        $entity = new BaseEntity();
        $entity->offsetSet("name", "Daniel Samson");
        $this->assertEquals("Daniel Samson", $entity->name);
    }

    public function test_offsetUnset()
    {
        $entity = new BaseEntity();
        $entity->offsetSet("name", "Daniel Samson");
        $this->assertEquals("Daniel Samson", $entity->name);
        $entity->offsetUnset("name");
        $this->assertFalse($entity->offsetExists("name"));
    }
}