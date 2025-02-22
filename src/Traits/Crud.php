<?php

namespace TeensyPHP\Traits;

use TeensyPHP\Exceptions\TeensyPHPException;

trait Crud
{
    public static \PDO $DB;

    protected static string $table = 'undefined';

    /**
     * @param int|string $id The id of the object to find
     * @param string $idName The name of the id column
     * @return static
     * @throws TeensyPHPException
     */
    public static function find(int|string $id, string $idName = "id"): static
    {
        $table = static::$table;
        $sql = "SELECT * FROM {$table} WHERE $idName = ?";
        $statement = self::$DB->prepare($sql);
        $result = $statement->execute([$id]);

        if ($result === false) {
            TeensyPHPException::throwNotFound();
        }

        $records = $statement->fetchAll(\PDO::FETCH_ASSOC);

        if (count($records) !== 1) {
            TeensyPHPException::throwNotFound();
        }

        return self::make($records[0]);
    }

    /**
     * @return self[]
     * @throws TeensyPHPException
     */
    public static function findAll(): array
    {
        $table = static::$table;
        $sql = "SELECT * FROM {$table}";
        $statement = self::$DB->prepare($sql);
        $result = $statement->execute();

        if ($result === false) {
            TeensyPHPException::throwNotFound();
        }

        $records = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return array_map(function ($record) {
            return self::make($record);
        }, $records);
    }

    public static function make(array $data = []): static
    {
        $object = new static();
        // get public properties of class
        $reflect = new \ReflectionClass(static::class);
        $properties   = $reflect->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $property) {
            if (isset($data[$property->name])) {
                $object->{$property->name} = $data[$property->name];
            }
        }
        return $object;
    }

    /**
     * @throws TeensyPHPException
     */
    public static function create(array $data): static
    {
        $table = static::$table;
        $sql = "INSERT INTO {$table} (";
        $values = [];
        foreach ($data as $key => $value) {
            $sql .= "`{$key}`, ";
            $values[] = $value;
        }
        $sql = rtrim($sql, ", ");
        $sql .= ") VALUES (";
        $sql .= implode(", ", array_fill(0, count($data), "?"));
        $sql .= ")";
        $statement = self::$DB->prepare($sql);
        $result = $statement->execute($values);

        if ($result === false) {
            throw new TeensyPHPException("Failed to create record");
        }

        // get the last inserted id
        $id = self::$DB->lastInsertId();
        return self::find($id);
    }

    /**
     * @param \ArrayAccess $data The data to update
     * @param int|string $id The id of the object to update
     * @param string $idName The name of the id column
     * @return static
     * @throws TeensyPHPException
     */
    public static function update(array $data, int|string $id, string $idName = "id"): static
    {
        $table = static::$table;
        $sql = "UPDATE {$table} SET ";
        $values = [];
        foreach ($data as $key => $value) {
            $sql .= "`{$key}` = ?, ";
            $values[] = $value;
        }
        $sql = rtrim($sql, ", ");
        $sql .= " WHERE $idName = ?";
        $statement = self::$DB->prepare($sql);
        $result = $statement->execute(array_merge($values, [$id]));

        if ($result === false) {
            throw new TeensyPHPException("Failed to update record");
        }

        // fetch the object
        return self::find($id);
    }

    /**
     * @param int|string $id The id of the object to delete
     * @param string $idName The name of the id column
     * @return bool True if the object was deleted
     */
    public static function delete(int|string $id, string $idName = "id"): bool
    {
        $table = static::$table;
        $sql = "DELETE FROM {$table} WHERE $idName = ?";
        $statement = self::$DB->prepare($sql);
        return $statement->execute([$id]);
    }
}