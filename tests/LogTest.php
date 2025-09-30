<?php
use PHPUnit\Framework\TestCase;
use TeensyPHP\Enums\LogLevelEnum;
use TeensyPHP\Utility\Log;

class LogTest extends TestCase
{
   public function test_debug_is_enabled()
   {
        Log::setLevel(LogLevelEnum::DEBUG);
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
   }
    
    public function test_info_is_enabled()
    {
        Log::setLevel(LogLevelEnum::INFO);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_warning_is_enabled()
    {
        Log::setLevel(LogLevelEnum::WARNING);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_error_is_enabled()
    {
        Log::setLevel(LogLevelEnum::ERROR);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_critical_is_enabled()
    {
        Log::setLevel(LogLevelEnum::CRITICAL);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_alert_is_enabled()
    {
        Log::setLevel(LogLevelEnum::ALERT);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_emergency_is_enabled()
    {
        Log::setLevel(LogLevelEnum::EMERGENCY);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertTrue(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }

    public function test_none_is_enabled()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::DEBUG));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::INFO));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::WARNING));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ERROR));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::CRITICAL));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::ALERT));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::EMERGENCY));
        $this->assertFalse(Log::isLogLevelEnabled(LogLevelEnum::NONE));
    }
    
}