<?php

namespace TeensyPHP\Enums;

/**
 * Log level enum
 */
enum LogLevelEnum: string
{
    case DEBUG = 'debug';
    case INFO = 'info';
    case WARNING = 'warning';
    case ERROR = 'error';
    case CRITICAL = 'critical';
    case ALERT = 'alert';
    case EMERGENCY = 'emergency';
    case NONE = 'none';

    /**
     * Get the log level from a string
     * @param string $level
     * @return LogLevelEnum
     */
    public static function fromString(string $level): LogLevelEnum
    {
        return self::tryFrom($level) ?? self::NONE;
    }

    /**
     * Get the string representation of the log level
     * @return string
     */
    public function toString(): string
    {
        return $this->value;
    }

    /**
     * Get the integer representation of the log level
     * @param LogLevelEnum $level
     * @return int|null
     */
    public static function toInt(LogLevelEnum $level): ?int
    {
        $levelOrder = [
            self::DEBUG,
            self::INFO,
            self::WARNING,
            self::ERROR,
            self::CRITICAL,
            self::ALERT,
            self::EMERGENCY,
            self::NONE,
        ];
        $index = array_search($level, $levelOrder);
        return $index !== false ? $index : null;
    }
}