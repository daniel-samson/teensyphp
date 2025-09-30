<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\Enums\LogLevelEnum;

class LogLevelEnumTest extends TestCase
{
    public function test_fromString()
    {
        $this->assertEquals(LogLevelEnum::DEBUG, LogLevelEnum::fromString('debug'));
    }

    public function test_toString()
    {
        $this->assertEquals('debug', LogLevelEnum::DEBUG->toString());
    }

    public function test_toInt()
    {
        $this->assertEquals(0, LogLevelEnum::toInt(LogLevelEnum::DEBUG));
        $this->assertEquals(1, LogLevelEnum::toInt(LogLevelEnum::INFO));
        $this->assertEquals(2, LogLevelEnum::toInt(LogLevelEnum::WARNING));
        $this->assertEquals(3, LogLevelEnum::toInt(LogLevelEnum::ERROR));
        $this->assertEquals(4, LogLevelEnum::toInt(LogLevelEnum::CRITICAL));
        $this->assertEquals(5, LogLevelEnum::toInt(LogLevelEnum::ALERT));
        $this->assertEquals(6, LogLevelEnum::toInt(LogLevelEnum::EMERGENCY));
        $this->assertEquals(7, LogLevelEnum::toInt(LogLevelEnum::NONE));
      
    }
}