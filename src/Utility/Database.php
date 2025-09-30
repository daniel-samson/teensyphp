<?php

namespace TeensyPHP\Utility;

/**
 * Database connection
 */
class Database
{
    protected static \PDO $PDO;
    protected static Database $instance;

    /**
     * Connect to the database
     * @param string $engine
     * @param string $database
     * @param string $host
     * @param int $port
     * @param string|null $username
     * @param string|null $password
     * @return Database
     */
    public static function connect(
        string $engine,
        string $database,
        string $host = '',
        int $port = 0,
        ?string $username = null,
        ?string $password = null
    ): Database
    {
        $dsn = self::dsn($engine, $database, $host, $port);
        self::$PDO = new \PDO($dsn, $username, $password);
        return self::instance();
    }

    /**
     * Get the database instance
     * @return Database
     */
    public static function instance(): Database
    {
        if (isset(self::$instance) === false) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Generate the DSN for the database
     * @param string $engine
     * @param string $database
     * @param string $host
     * @param int $port
     * @return string
     */
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

    /**
     * Get the database connection
     * @return \PDO
     */
    public static function connection(): \PDO
    {
        return self::$PDO;
    }
}