<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\Utility\Config;

class ConfigTest extends TestCase
{
    public function beforeEach(): void
    {
        shell_exec("unset DB_DATABASE");
        shell_exec("unset DB_USERNAME");
        shell_exec("unset DB_PASSWORD");
        shell_exec("unset DB_HOST");
        shell_exec("unset DB_PORT");
        shell_exec("unset DB_ENGINE");
    }

    public function test_loadEnvFile()
    {
        Config::loadEnvFile(__DIR__ . "/_data");
        $this->assertEquals("teensyphp", getenv("DB_DATABASE"));
        $this->assertEquals("teensyphp", getenv("DB_USERNAME"));
        $this->assertEquals("secret", getenv("DB_PASSWORD"));
        $this->assertEquals("localhost", getenv("DB_HOST"));
        $this->assertEquals("3306", getenv("DB_PORT"));
        $this->assertEquals("mysql", getenv("DB_ENGINE"));
        $this->assertTrue(true);
    }

    public function test_get()
    {
        Config::loadEnvFile(__DIR__ . "/_data");
        $this->assertEquals("teensyphp", Config::get("DB_DATABASE"));
    }

    public function test_loadEnvFile_no_file()
    {
        Config::loadEnvFile(__DIR__ . "/_data2");
        $this->assertFalse(getenv("DB_DATABASE"));
    }

    public function test_get_default()
    {
        $this->assertEquals("default", Config::get("DOES_NOT_EXIST", "default"));
    }
}