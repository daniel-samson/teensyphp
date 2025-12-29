---
sidebar_position: 6
---

# Error Handling

The TeensyPHP `router()` function contains a built-in error handler.

## Throwing Errors

```php
throw new \Error("Something went wrong", 500);
```

Output:

```json
{
  "error": "Something went wrong"
}
```

## TeensyPHPException

```php
use TeensyPHP\Exceptions\TeensyPHPException;

throw new TeensyPHPException("Error message", 500);
```

### Helper Methods

```php
TeensyPHPException::throwNotFound();     // 404
TeensyPHPException::throwBadRequest();   // 400
TeensyPHPException::throwUnauthorized(); // 403
```

Default (no arguments): "Internal Server Error" with status code 500.

## Exit Early

Use `stop()` instead of `exit()` for testable code:

```php
stop(int $code = 0)
```

Example:

```php
function Authentication() {
    if (!isAuthenticated()) {
        render(401, json_out(['error' => 'Unauthorized']));
        stop(1);
    }
}
```

Using `stop()` enables you to write tests that capture controller output.
