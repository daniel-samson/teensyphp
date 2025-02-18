<?php

require_once __DIR__ . "/_data/BaseEntity.php";
require_once __DIR__ . "/_data/MockPdo.php";
require_once __DIR__ . "/_data/MockPdoStatement.php";

use PHPUnit\Framework\TestCase;
use TeensyPHP\Exceptions\TeensyPHPException;

class CrudTest extends TestCase
{
    public function test_find()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->assertEquals($entity, BaseEntity::find(1));

        // test not found
        $pdoStatement->fetchAllReturns = [];
        $pdo->pdoStatement = $pdoStatement;
        // set up the mock pdo
        BaseEntity::$DB = $pdo;
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Not Found");
        BaseEntity::find(1);

    }

    public function test_find_not_found2()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        // test not found
        $pdoStatement->executeReturns = false;
        $pdo->pdoStatement = $pdoStatement;
        // set up the mock pdo
        BaseEntity::$DB = $pdo;
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Not Found");
        BaseEntity::find(1);
    }

    public function test_findAll()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->assertEquals([$entity], BaseEntity::findAll());
    }

    public function test_find_all_not_found2()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdoStatement->executeReturns = false;
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Not Found");
        BaseEntity::findAll();
    }

    public function test_make()
    {
        $actual = BaseEntity::make(["id" => 2]);

        $expected = new BaseEntity();
        $expected->id = 2;
        $this->assertEquals($expected, $actual);
    }

    public function test_create()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->assertEquals($entity, BaseEntity::create($entityAsArray));

        // test not found
        $pdoStatement->fetchAllReturns = [];
        $pdo->pdoStatement = $pdoStatement;
        // set up the mock pdo
        BaseEntity::$DB = $pdo;
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Not Found");
        BaseEntity::create($entityAsArray);
    }

    public function test_create_failure()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entity->name = "Daniel Samson";
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdoStatement->executeReturns = false;
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Failed to create record");
        BaseEntity::create($entityAsArray);
    }

    public function test_update()
    {
        $entity = new BaseEntity();
        $entity->id = 1;

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [["id" => 1, "name" => "Daniel Samson"]];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $expected = new BaseEntity();
        $expected->id = 1;
        $expected->name = "Daniel Samson";

        $this->assertEquals($expected, BaseEntity::update(["name" => "Daniel Samson"], 1));

        // test not found
        $pdoStatement->fetchAllReturns = [];
        $pdo->pdoStatement = $pdoStatement;
        // set up the mock pdo
        BaseEntity::$DB = $pdo;
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(404);
        $this->expectExceptionMessage("Not Found");
        BaseEntity::update(["name" => "Daniel Samson"], 2);
    }

    public function test_update_not_found2()
    {
        $entity = new BaseEntity();
        $entity->id = 1;

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [["id" => 1, "name" => "Daniel Samson"]];
        $pdoStatement->executeReturns = false;

        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;
        $this->expectException(TeensyPHPException::class);
        $this->expectExceptionCode(500);
        $this->expectExceptionMessage("Failed to update record");
        BaseEntity::update(["name" => "Daniel Samson"], 2);
    }

    public function test_delete()
    {
        $entity = new BaseEntity();
        $entity->id = 1;
        $entityAsArray = $entity->toArray();

        $pdo = new MockPdo("sqlite:mock.db");
        $pdoStatement = new MockPdoStatement();
        $pdoStatement->fetchAllReturns = [$entityAsArray];
        $pdo->pdoStatement = $pdoStatement;

        // set up the mock pdo
        BaseEntity::$DB = $pdo;

        $this->assertEquals(true, BaseEntity::delete(1));
    }
}