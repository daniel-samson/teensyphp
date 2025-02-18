<?php
require_once __DIR__ . "/MockPdoStatement.php";

class MockPdo extends \PDO
{
    public MockPdoStatement $pdoStatement;
    public array $queryResult = [];
    public array $lastPrepareArgs = [];

    public function lastInsertId(?string $name = null): string|false
    {
        return 1;
    }

    public function prepare(...$args): \PDOStatement
    {
        $this->lastPrepareArgs = $args;
        return $this->pdoStatement;
    }
}