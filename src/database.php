<?php

use TeensyPhp\DataMapper;

/**
 * Setup a PDO connection
 * @param $dsn string
 * eg.
 * mysql:host=localhost;dbname=test
 * pgsql:host=localhost;port=5432;dbname=testdb;user=bruce;password=mypass
 * sqlite:/opt/databases/mydb.sqlite
 * sqlite::memory:
 * new PDO("sqlsrv:Server=localhost;Database=testdb", "UserName", "Password");
 *
 * @param $username string
 * @param $password string
 * @param $options array
 * @return PDO
 */
function database(
    string $dsn,
    ?string $username = null,
    ?string $password = null,
    ?array $options = null
): PDO {
    return new PDO($dsn, $username, $password, $options);
}

/**
 *
 * @param $pdo PDO
 * @param $dataMapper DataMapper
 * @param $sql string
 * @param $params array
 * @return PDOStatement
 * @throws Error
 * @throws Exception
 * @throws PDOException
 * @example <?php
 * $pdo = database('sqlite::memory:');
 * $sql = "SELECT * FROM users WHERE id = :id";
 * $dataMapper = new User(['id' => 1]);
 * $sth = database_query($pdo, $dataMapper, $sql);
 * $users = [];
 * while ($user = $sth->fetchObject(User::class)) {
 *   $users[] = $user;
 * }
 }
 */
function database_query(
    PDO $pdo,
    DataMapper $dataMapper,
    string $sql,
): PDOStatement {
    $sth = $pdo->prepare($sql);
    // find params
    $params = get_object_vars($dataMapper);
    foreach ($params as $key => $value) {
        if (strstr($sql, ":$key") !== false) {
            $sth->bindParam(":$key", $value, to_pdo_param_type($dataMapper->{$key}));
        }
    }

    $sth->execute();

    return $sth;
}

/**
 * Detects the PDO param type
 * @param $value mixed
 * @return int
 */
function to_pdo_param_type($value): int
{
    switch (gettype($value)) {
        case 'boolean':
            return PDO::PARAM_BOOL;
        case 'integer':
            return PDO::PARAM_INT;
        case 'NULL':
            return PDO::PARAM_NULL;
        case 'array':
            // @todo: support to many relationships
            throw new Error('Arrays are not supported as PDO parameters');
        case 'object':
            // @todo: support to one relationships
            throw new Error('Objects are not supported as PDO parameters');
        default:
            return PDO::PARAM_STR;
    }
}
