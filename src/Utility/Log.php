<?php

namespace TeensyPHP\Utility;

use TeensyPHP\Enums\LogLevelEnum;

/**
 * Log a message
 */
class Log
{
    protected static LogLevelEnum $level = LogLevelEnum::INFO;

    /**
     * Set the log level
     * @param LogLevelEnum $level
     * @return void
     */
    public static function setLevel(LogLevelEnum $level): void
    {
        self::$level = $level;
    }

    /**
     * Log a message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function log(string $message) : bool
    {
        if (defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__')) {
            // for testing purposes, do not log, as it will cause tests to be marked as risky
            return false;
        }

        error_log($message);
        return true;
    }

    /**
     * Log a debug message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function debug(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::DEBUG)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log an info message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function info(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::INFO)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log a warning message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function warning(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::WARNING)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log an error message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function error(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::ERROR)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log a critical message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function critical(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::CRITICAL)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log an alert message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function alert(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::ALERT)) {
            return self::log($message);
        }

        return false;
    }
    
    /**
     * Log an emergency message
     * @param string $message
     * @return bool true if logged, false if not logged
     */
    public static function emergency(string $message): bool
    {
        if (self::isLogLevelEnabled(LogLevelEnum::EMERGENCY)) {
            return self::log($message);
        }

        return false;
    }

    /**
     * Check if a log level is enabled
     * @param LogLevelEnum $level
     * @return bool true if enabled, false if not enabled
     */
    public static function isLogLevelEnabled(LogLevelEnum $level): bool
    {
        if ($level === LogLevelEnum::NONE) {
            return false;
        }

        $logLevelInt = LogLevelEnum::toInt(self::$level);
        $levelInt = LogLevelEnum::toInt($level);

        return $levelInt >= $logLevelInt;
    }
}