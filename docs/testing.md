---
sidebar_position: 7
---

# Testing

TeensyPHP supports testing with PHPUnit.

## Testing Actions

```php
<?php

use PHPUnit\Framework\TestCase;

class RouterTest extends TestCase
{
    public function test_action()
    {
        $_GET['url'] = 'baz';
        router(function () {
            route(method(GET), url_path('/baz'), function() {
                json_out(['data' => 'foo']);
            });
        });
        $this->expectOutputString('{"data":"foo"}');
        $this->assertEquals(200, http_response_code());
    }
}
```

## Using stop() for Testability

Use `stop()` instead of `exit()` to enable testing:

```php
function authentication() {
    if (!isAuthenticated()) {
        render(401, json_out(['error' => 'Unauthorized']));
        stop(1);
    }
}
```

This allows test frameworks to capture output without terminating the process.
