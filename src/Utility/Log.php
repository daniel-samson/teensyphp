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
     * @return void
     */
    public static function log(string $message)
    {
        if (defined('PHPUNIT_COMPOSER_INSTALL') || defined('__PHPUNIT_PHAR__')) {
            // for testing purposes, do not log, as it will cause tests to be marked as risky
            return;
        }

        error_log($message);
    }

    public static function debug(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::DEBUG)) {
            self::log($message);
        }
    }
    
    public static function info(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::INFO)) {
            self::log($message);
        }
    }
    
    
    public static function warning(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::WARNING)) {
            self::log($message);
        }
    }
    
    
    public static function error(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::ERROR)) {
            self::log($message);
        }
    }
    
    
    public static function critical(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::CRITICAL)) {
            self::log($message);
        }
    }
    
    
    public static function alert(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::ALERT)) {
            self::log($message);
        }
    }
    
    public static function emergency(string $message): void
    {
        if (self::isLogLevelEnabled(LogLevelEnum::EMERGENCY)) {
            self::log($message);
        }
    }

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