---
sidebar_position: 6
---

# Error Handling

## Basic Error Handling

```php
router(function() {
    route(method(GET), url_path("/api/users/:id"), function() {
        $id = $_GET[':id'];
        $user = User::find($id);

        if (!$user) {
            throw new \Error("User not found", 404);
        }

        render(200, json_out($user->toArray()));
    });
});
```

**Output when user not found:**
```json
{
    "error": "User not found"
}
```

## TeensyPHP Exceptions

```php
use TeensyPHP\Exceptions\TeensyPHPException;

router(function() {
    route(method(GET), url_path("/api/resource"), function() {
        // Not found
        TeensyPHPException::throwNotFound();
    });

    route(method(POST), url_path("/api/resource"), function() {
        $data = json_in();

        if (empty($data['name'])) {
            TeensyPHPException::throwBadRequest();
        }

        // Process...
    });

    route(method(GET), url_path("/api/admin"), function() {
        if (!isAdmin()) {
            TeensyPHPException::throwUnauthorized();
        }

        render(200, json_out(["admin" => true]));
    });
});
```

## Custom Error Messages

```php
use TeensyPHP\Exceptions\TeensyPHPException;

router(function() {
    route(method(POST), url_path("/api/users"), function() {
        $data = json_in();

        if (empty($data['email'])) {
            throw new TeensyPHPException("Email is required", 400);
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new TeensyPHPException("Invalid email format", 400);
        }

        if (User::findByEmail($data['email'])) {
            throw new TeensyPHPException("Email already exists", 409);
        }

        $user = User::create($data);
        render(201, json_out($user->toArray()));
    });
});
```

## Early Exit with stop()

```php
function requireAuth()
{
    $token = request_header('Authorization');

    if (empty($token)) {
        render(401, json_out(['error' => 'Unauthorized']));
        stop();  // Stops execution here
    }

    if (!validateToken($token)) {
        render(403, json_out(['error' => 'Invalid token']));
        stop();
    }
}

router(function() {
    route(method(GET), url_path("/api/protected"), function() {
        requireAuth();
        // Only reaches here if authenticated
        render(200, json_out(["data" => "secret"]));
    });
});
```

## Try-Catch Pattern

```php
router(function() {
    route(method(POST), url_path("/api/process"), function() {
        try {
            $data = json_in();
            $result = processComplexOperation($data);
            render(200, json_out(["result" => $result]));

        } catch (\InvalidArgumentException $e) {
            render(400, json_out(["error" => $e->getMessage()]));

        } catch (\RuntimeException $e) {
            render(500, json_out(["error" => "Processing failed"]));
            error_log($e->getMessage());

        } catch (\Exception $e) {
            render(500, json_out(["error" => "Internal server error"]));
            error_log($e->getMessage());
        }
    });
});
```

## How Error Handling Works

The TeensyPHP `router()` function includes a built-in error handler that catches exceptions and renders JSON error responses automatically.

When you throw an exception:
1. The router catches it
2. Uses the exception code as the HTTP status code
3. Returns the exception message as a JSON error response

For more control, use try-catch blocks or the `stop()` function to handle errors manually.

## Function Reference

### TeensyPHPException

```php
use TeensyPHP\Exceptions\TeensyPHPException;

throw new TeensyPHPException(string $message, int $code);
```

| Parameter | Type | Description |
|-----------|------|-------------|
| `$message` | string | Error message (default: "Internal Server Error") |
| `$code` | int | HTTP status code (default: 500) |

### Helper Methods

| Method | Status Code | Description |
|--------|-------------|-------------|
| `TeensyPHPException::throwNotFound()` | 404 | Resource not found |
| `TeensyPHPException::throwBadRequest()` | 400 | Invalid request |
| `TeensyPHPException::throwUnauthorized()` | 403 | Access denied |

### stop()

```php
stop(int $code = 0): void
```

Halts script execution. Use instead of `exit()` to keep code testable.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$code` | int | Exit code (default: 0) |

## Common HTTP Error Codes

| Code | Name | When to Use |
|------|------|-------------|
| 400 | Bad Request | Invalid input, validation errors |
| 401 | Unauthorized | Missing authentication |
| 403 | Forbidden | Valid auth but no permission |
| 404 | Not Found | Resource doesn't exist |
| 405 | Method Not Allowed | Wrong HTTP method |
| 409 | Conflict | Duplicate resource |
| 422 | Unprocessable Entity | Semantic validation errors |
| 500 | Internal Server Error | Server-side failures |
