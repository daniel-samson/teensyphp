<?php

namespace TeensyPHP\Utility;

class Database
{
    protected static \PDO $PDO;
    protected static Database $instance;

    public static function connect(
        string $engine,
        string $database,
        string $host = '',
        int $port = 0,
        string $username = NULL,
        string $password = NULL
    ): Database
    {
        $dsn = self::dsn($engine, $database, $host, $port);
        self::$PDO = new \PDO($dsn, $username, $password);
        return self::instance();
    }


    public static function instance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function dsn($engine, $database, $host = '', $port = 0): string
    {
        if ($host === '') {
            $host = '127.0.0.1';
        }

        switch ($engine) {
            case 'mysql':
                if ($port === 0) {
                    $port = 3306;
                }
                $dsn = $engine . ':host=' . $host . ';port=' . $port . ';dbname=' . $database;
                break;
            case 'pgsql':
                if ($port === 0) {
                    $port = 5432;
                }
                $dsn = $engine . ':host=' . $host . ';port=' . $port . ';dbname=' . $database;
                break;
            case 'sqlite':
                $dsn = $engine . ':' . $database;
                break;
            case 'sqlsrv':
                if ($port === 0) {
                    $port = 1433;
                }
                $dsn = "$engine:";
                $dsn .= "Server=$host;";
                if ($port) {
                    $dsn .= "Port=$port;";
                }
                $dsn .= "Database=$database";
                break;
            default:
                throw new \InvalidArgumentException("Invalid database engine");
        }

        return $dsn;
    }

    public static function connection(): \PDO
    {
        return self::$PDO;
    }
}