<?php

class MockPdoStatement extends \PDOStatement
{
    public array $lastExecuteParams = [];
    public bool $executeReturns = true;
    public array $lastFetchAllParams = [];
    public array $fetchAllReturns = [];
    public function execute($params = []): bool
    {
        $this->lastExecuteParams = $params;
        return $this->executeReturns;
    }

    public function fetchAll(int $mode = null, ...$args): array
    {
        $this->lastFetchAllParams = $args;
        return $this->fetchAllReturns;
    }
}