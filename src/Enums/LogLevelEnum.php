<?php

namespace TeensyPHP\Enums;

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

    public static function fromString(string $level): LogLevelEnum
    {
        return self::tryFrom($level) ?? self::NONE;
    }

    public function toString(): string
    {
        return $this->value;
    }

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