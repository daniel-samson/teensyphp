---
sidebar_position: 4
---

# Logging

## Quick Start

```php
use TeensyPHP\Utility\Log;

// Log messages at different levels
Log::debug('User login attempt', ['email' => 'user@example.com']);
Log::info('User logged in successfully', ['user_id' => 42]);
Log::warning('Rate limit approaching', ['requests' => 95, 'limit' => 100]);
Log::error('Database connection failed', ['host' => 'localhost']);
Log::critical('Payment processing unavailable');
```

**Output (to stderr):**
```
[2024-01-15 10:30:45] DEBUG: User login attempt {"email":"user@example.com"}
[2024-01-15 10:30:46] INFO: User logged in successfully {"user_id":42}
[2024-01-15 10:30:47] WARNING: Rate limit approaching {"requests":95,"limit":100}
```

## Log Levels

```php
use TeensyPHP\Utility\Log;

// Debug - detailed debugging information
Log::debug('Query executed', ['sql' => 'SELECT * FROM users', 'time' => '0.023s']);

// Info - general informational messages
Log::info('Order placed', ['order_id' => 12345, 'total' => 99.99]);

// Warning - exceptional occurrences that are not errors
Log::warning('Deprecated API endpoint used', ['endpoint' => '/v1/users']);

// Error - runtime errors that don't require immediate action
Log::error('Failed to send email', ['to' => 'user@example.com', 'reason' => 'SMTP timeout']);

// Critical - critical conditions requiring immediate attention
Log::critical('Database server unreachable');

// Alert - action must be taken immediately
Log::alert('Disk space critically low', ['free' => '500MB']);

// Emergency - system is unusable
Log::emergency('Application crashed');
```

## Configuration

Set the log level in your `.env` file:

```ini
# Only show warnings and above
LOG_LEVEL=warning

# Show all messages (development)
LOG_LEVEL=debug

# Production - only errors and above
LOG_LEVEL=error
```

## Using in Routes

```php
use TeensyPHP\Utility\Log;

router(function() {
    route(method(POST), url_path('/api/orders'), function() {
        $data = json_in();

        Log::info('Order creation started', ['data' => $data]);

        try {
            $order = Order::create($data);
            Log::info('Order created successfully', ['order_id' => $order->id]);
            render(201, json_out($order->toArray()));

        } catch (\Exception $e) {
            Log::error('Order creation failed', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            render(500, json_out(['error' => 'Order creation failed']));
        }
    });
});
```

## Middleware Logging

```php
function requestLogger(): callable
{
    return function() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        $ip = $_SERVER['REMOTE_ADDR'];

        Log::info('Incoming request', [
            'method' => $method,
            'path' => $path,
            'ip' => $ip,
        ]);
    };
}

router(function() {
    route(method(GET), url_path('/api/users'), middleware(
        requestLogger(),
        function() {
            render(200, json_out(['users' => []]));
        }
    ));
});
```

## Error Handler Logging

```php
router(function() {
    route(method(GET), url_path('/api/resource'), function() {
        try {
            // Some operation that might fail
            $result = riskyOperation();
            render(200, json_out($result));

        } catch (\InvalidArgumentException $e) {
            Log::warning('Invalid input', ['error' => $e->getMessage()]);
            render(400, json_out(['error' => $e->getMessage()]));

        } catch (\RuntimeException $e) {
            Log::error('Operation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            render(500, json_out(['error' => 'Internal error']));
        }
    });
});
```

## How It Works

The `Log` class provides PSR-3 style logging:

1. **Log level filtering** - Messages below the configured `LOG_LEVEL` are ignored
2. **Structured context** - Pass an array as the second argument for additional data
3. **Output to stderr** - Messages are written to PHP's error log (typically stderr)
4. **Automatic formatting** - Timestamps and context are formatted automatically

## Function Reference

### Log Methods

```php
Log::debug(string $message, array $context = []): void
Log::info(string $message, array $context = []): void
Log::warning(string $message, array $context = []): void
Log::error(string $message, array $context = []): void
Log::critical(string $message, array $context = []): void
Log::alert(string $message, array $context = []): void
Log::emergency(string $message, array $context = []): void
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | string | Log message |
| `$context` | array | Additional data to include (optional) |

## Log Levels (Priority Order)

| Level | Value | When to Use |
|-------|-------|-------------|
| `debug` | 7 | Detailed debugging (development only) |
| `info` | 6 | General information (user actions, etc.) |
| `warning` | 5 | Abnormal but handled situations |
| `error` | 4 | Errors that need attention |
| `critical` | 3 | Critical failures |
| `alert` | 2 | Immediate action required |
| `emergency` | 1 | System is unusable |

## Environment Configuration

| LOG_LEVEL | Shows |
|-----------|-------|
| `debug` | All messages |
| `info` | info and above |
| `warning` | warning and above |
| `error` | error and above |
| `critical` | critical and above |
| `alert` | alert and emergency only |
| `emergency` | emergency only |
