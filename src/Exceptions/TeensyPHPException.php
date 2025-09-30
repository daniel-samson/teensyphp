<?php

namespace TeensyPHP\Exceptions;

/**
 * TeensyPHPException
 */
class TeensyPHPException extends \Exception
{
    /**
     * @param string $message
     * @param int $code
     * @param \Throwable|null $previous
     */
    public function __construct(string $message = "Internal Server Error", int $code = 500, ?\Throwable $previous = null)
    {
        parent::__construct();
        $this->message = $message;
        $this->code = $code;
    }

    public function __toString(): string
    {
        return $this->message;
    }


    /**
     * @return array
     */
    public function getPrettyTrace(): array
    {
        $trace = debug_backtrace();

        // hide lines that contain vendor
        $trace = array_filter($trace, function ($trace) {
            return !str_contains($trace['file'], 'vendor');
        });
        return $trace;
    }


    /**
     * @return string
     */
    public function getPrettyTraceAsString(): string
    {
        $traceString = '';
        $trace = $this->getPrettyTrace();
        foreach ($trace as $traceLine) {
            $traceString .= $traceLine['file'] . ":" . $traceLine['line'] . " - " . $traceLine['function'] . PHP_EOL;
        }
        return $traceString;
    }

    /**
     * @throws TeensyPHPException
     */
    public static function throwNotFound(string $message = "Not Found", int $code = 404): static
    {
        throw new static($message, $code);
    }

    /**
     * @throws TeensyPHPException
     */
    public static function throwBadRequest(string $message = "Bad Request", int $code = 400): static
    {
        throw new static($message, $code);
    }

    /**
     * @throws TeensyPHPException
     */
    public static function throwUnauthorized(string $message = "Unauthorized", int $code = 401): static
    {
        throw new static($message, $code);
    }
}