<?php

use PhpParser\Node\Stmt\While_;
use PHPUnit\Framework\TestCase;
use TeensyPhp\Tests\CustomerModel;

class DatabaseTest extends TestCase
{
    private PDO $pdo;

    protected function setUp(): void
    {
        $this->pdo = new PDO('sqlite::memory:');
        $this->pdo->exec('CREATE TABLE customers (id INTEGER PRIMARY KEY, name TEXT, email TEXT)');
        $this->pdo->exec('INSERT INTO customers (name, email) VALUES ("John Doe", "email@example.com")');
    }

    public function test_database()
    {
        $actual = database('sqlite::memory:');
        $this->assertInstanceOf(PDO::class, $actual);
    }

    public function test_database_query()
    {
        $sth = database_query(
            $this->pdo,
            new CustomerModel(['id' => 1]),
            'SELECT * FROM customers WHERE id = :id'
        );

        $actual = $sth->fetchObject(CustomerModel::class);

        $this->assertInstanceOf(CustomerModel::class, $actual);
        $this->assertEquals(1, $actual->id);
        $this->assertEquals('John Doe', $actual->name);
        $this->assertEquals('email@example.com', $actual->email);
    }
}
