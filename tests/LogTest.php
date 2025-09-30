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
    

    public function test_log()
    {
        $this->assertFalse(Log::log('test'));
    }

    public function test_debug()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::debug('test'));

        Log::setLevel(LogLevelEnum::DEBUG);
        $this->assertFalse(Log::debug('test'));
    }
    
    public function test_info()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::info('test'));

        Log::setLevel(LogLevelEnum::INFO);
        $this->assertFalse(Log::info('test'));
    }
    
    
    public function test_warning()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::warning('test'));

        Log::setLevel(LogLevelEnum::WARNING);
        $this->assertFalse(Log::warning('test'));
    }
    
    public function test_error()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::error('test'));

        Log::setLevel(LogLevelEnum::ERROR);
        $this->assertFalse(Log::error('test'));
    }

    public function test_critical()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::critical('test'));

        Log::setLevel(LogLevelEnum::CRITICAL);
        $this->assertFalse(Log::critical('test'));
    }
    
    public function test_alert()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::alert('test'));

        Log::setLevel(LogLevelEnum::ALERT);
        $this->assertFalse(Log::alert('test'));
    }

    public function test_emergency()
    {
        Log::setLevel(LogLevelEnum::NONE);
        $this->assertFalse(Log::emergency('test'));

        Log::setLevel(LogLevelEnum::EMERGENCY);
        $this->assertFalse(Log::emergency('test'));
    }
}