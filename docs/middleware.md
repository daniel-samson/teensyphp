---
sidebar_position: 5
---

# Middleware

## Authentication Middleware

```php
// Middleware/Authentication.php
class Authentication
{
    public function __invoke()
    {
        $token = request_header('Authorization');

        if (empty($token)) {
            render(401, json_out(["error" => "No token provided"]));
            stop();
        }

        if (!$this->isValidToken($token)) {
            render(403, json_out(["error" => "Invalid token"]));
            stop();
        }

        // Token is valid, continue to next middleware/action
    }

    private function isValidToken(string $token): bool
    {
        // Validate token logic here
        return str_starts_with($token, 'Bearer ');
    }
}

// Using the middleware
router(function() {
    route(method(GET), url_path("/api/profile"), middleware(
        Authentication::class,
        function() {
            render(200, json_out(["user" => "John Doe"]));
        }
    ));
});
```

## Multiple Middleware

```php
class RateLimiter
{
    public function __invoke()
    {
        $ip = $_SERVER['REMOTE_ADDR'];
        if ($this->isRateLimited($ip)) {
            render(429, json_out(["error" => "Too many requests"]));
            stop();
        }
    }

    private function isRateLimited(string $ip): bool
    {
        // Rate limiting logic
        return false;
    }
}

class JsonContentType
{
    public function __invoke()
    {
        if (!accept(JSON_CONTENT)) {
            render(406, json_out(["error" => "JSON required"]));
            stop();
        }
    }
}

// Chain multiple middleware
router(function() {
    route(method(POST), url_path("/api/data"), middleware(
        RateLimiter::class,
        JsonContentType::class,
        Authentication::class,
        function() {
            $data = json_in();
            render(200, json_out(["received" => $data]));
        }
    ));
});
```

## Function-Based Middleware

```php
// middleware/cors.php
function cors(): callable
{
    return function() {
        response_header("Access-Control-Allow-Origin", "*");
        response_header("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
        response_header("Access-Control-Allow-Headers", "Content-Type, Authorization");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            render(204, "");
            stop();
        }
    };
}

// middleware/logging.php
function logging(): callable
{
    return function() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = $_SERVER['REQUEST_URI'];
        error_log("[{$method}] {$path}");
    };
}

// Using function-based middleware
router(function() {
    route(method(GET), url_path("/api/users"), middleware(
        cors(),
        logging(),
        function() {
            render(200, json_out(["users" => []]));
        }
    ));
});
```

## Global Middleware Pattern

```php
// Apply middleware to all routes in a group
router(function() {
    routerGroup("/api", function() {
        // All /api routes get CORS and logging
        route(method(GET), url_path("/users"), middleware(
            cors(),
            logging(),
            function() {
                render(200, json_out(["users" => []]));
            }
        ));

        route(method(GET), url_path("/posts"), middleware(
            cors(),
            logging(),
            function() {
                render(200, json_out(["posts" => []]));
            }
        ));
    });
});
```

## How Middleware Works

Middleware functions are executed in order before the final action. Each middleware can:

1. **Perform checks** - Validate authentication, rate limits, content types
2. **Modify state** - Set headers, log requests, prepare data
3. **Stop execution** - Call `stop()` to halt the middleware chain and prevent the action from running

If a middleware doesn't call `stop()`, execution continues to the next middleware or the final action.

## Function Reference

### middleware()

```php
middleware(callable|string ...$callables): callable
```

Chains multiple callables (functions or class names) together. Returns a callable that executes each in order.

| Parameter | Type | Description |
|-----------|------|-------------|
| `...$callables` | callable\|string | Functions or invokable class names |

### stop()

```php
stop(int $code = 0): void
```

Halts execution of the middleware chain. Use instead of `exit()` for testability.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$code` | int | Exit code (default: 0) |

## Middleware Patterns

| Pattern | Use Case |
|---------|----------|
| Authentication | Verify user identity |
| Authorization | Check user permissions |
| Rate Limiting | Prevent abuse |
| CORS | Cross-origin requests |
| Logging | Request tracking |
| Validation | Input validation |
| Caching | Response caching headers |
