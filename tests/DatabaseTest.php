<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\Utility\Database;

class DatabaseTest extends TestCase
{

    public function test_dsn_mysql_default_port()
    {
        $dsn = Database::dsn('mysql', 'testdb');
        $this->assertEquals('mysql:host=127.0.0.1;port=3306;dbname=testdb', $dsn);
    }

    public function test_dsn_mysql_custom_host_and_port()
    {
        $dsn = Database::dsn('mysql', 'testdb', 'localhost', 3307);
        $this->assertEquals('mysql:host=localhost;port=3307;dbname=testdb', $dsn);
    }

    public function test_dsn_pgsql_default_port()
    {
        $dsn = Database::dsn('pgsql', 'testdb');
        $this->assertEquals('pgsql:host=127.0.0.1;port=5432;dbname=testdb', $dsn);
    }

    public function test_dsn_pgsql_custom_host_and_port()
    {
        $dsn = Database::dsn('pgsql', 'testdb', 'localhost', 5433);
        $this->assertEquals('pgsql:host=localhost;port=5433;dbname=testdb', $dsn);
    }

    public function test_dsn_sqlite()
    {
        $dsn = Database::dsn('sqlite', 'test.db');
        $this->assertEquals('sqlite:test.db', $dsn);
    }

    public function test_dsn_sqlsrv_default_port()
    {
        $dsn = Database::dsn('sqlsrv', 'testdb');
        $this->assertEquals('sqlsrv:Server=127.0.0.1;Port=1433;Database=testdb', $dsn);
    }

    public function test_dsn_sqlsrv_custom_host_and_port()
    {
        $dsn = Database::dsn('sqlsrv', 'testdb', 'localhost', 1434);
        $this->assertEquals('sqlsrv:Server=localhost;Port=1434;Database=testdb', $dsn);
    }

    public function test_dsn_invalid_engine()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid database engine');
        Database::dsn('invalid', 'testdb');
    }

    public function test_connect_sqlite()
    {
        $database = Database::connect('sqlite', ':memory:');
        $this->assertInstanceOf(Database::class, $database);
    }

    public function test_instance_singleton()
    {
        Database::connect('sqlite', ':memory:');
        $instance1 = Database::instance();
        $instance2 = Database::instance();
        $this->assertSame($instance1, $instance2);
    }

    public function test_connection_returns_pdo()
    {
        Database::connect('sqlite', ':memory:');
        $pdo = Database::connection();
        $this->assertInstanceOf(PDO::class, $pdo);
    }

    public function test_connect_with_credentials()
    {
        $database = Database::connect('sqlite', ':memory:', '', 0, 'user', 'pass');
        $this->assertInstanceOf(Database::class, $database);
    }

    public function test_dsn_empty_host_defaults_to_localhost()
    {
        $dsn = Database::dsn('mysql', 'testdb', '');
        $this->assertEquals('mysql:host=127.0.0.1;port=3306;dbname=testdb', $dsn);
    }

    public function test_dsn_zero_port_uses_defaults()
    {
        $dsn = Database::dsn('mysql', 'testdb', 'localhost', 0);
        $this->assertEquals('mysql:host=localhost;port=3306;dbname=testdb', $dsn);
    }

    public function test_sqlsrv_zero_port_uses_default()
    {
        $dsn = Database::dsn('sqlsrv', 'testdb', 'localhost', 0);
        $this->assertEquals('sqlsrv:Server=localhost;Port=1433;Database=testdb', $dsn);
    }
}