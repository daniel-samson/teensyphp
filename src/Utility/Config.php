<?php

namespace TeensyPHP\Utility;

class Config
{
    public static function get(string $key, string|array|bool $default = false): string|array|bool
    {
        $env = getenv($key);
        if ($env !== false) {
            return $env;
        }

        return $default;
    }

    /**
     * Finds .env file and loads envs into environment
     * @param string $dir Directory to search for .env file
     * @return bool true if file was found and loaded
     */
    public static function loadEnvFile(string $dir): bool
    {
        $envPath = $dir . "/.env";
        if (file_exists($envPath) === false) {
            return false;
        }

        $env = parse_ini_file($envPath, true);
        foreach ($env as $key => $value) {
            putenv("{$key}={$value}");
        }

        return true;
    }
}