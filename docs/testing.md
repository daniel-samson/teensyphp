---
sidebar_position: 7
---

# Testing

## Basic Route Test

```php
<?php

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    public function test_get_users_returns_json()
    {
        // Simulate GET request to /users
        $_GET['url'] = 'users';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        router(function() {
            route(method(GET), url_path('/users'), function() {
                json_out(['users' => ['Alice', 'Bob']]);
            });
        });

        $this->expectOutputString('{"users":["Alice","Bob"]}');
        $this->assertEquals(200, http_response_code());
    }
}
```

## Testing POST Requests

```php
<?php

use PHPUnit\Framework\TestCase;

class CreateUserTest extends TestCase
{
    public function test_create_user_returns_201()
    {
        // Simulate POST request
        $_GET['url'] = 'users';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Mock JSON input (you may need to mock json_in())
        router(function() {
            route(method(POST), url_path('/users'), function() {
                render(201, json_out(['created' => true]));
            });
        });

        $this->expectOutputString('{"created":true}');
        $this->assertEquals(201, http_response_code());
    }
}
```

## Testing with URL Parameters

```php
<?php

use PHPUnit\Framework\TestCase;

class UserDetailTest extends TestCase
{
    public function test_get_user_by_id()
    {
        $_GET['url'] = 'users/123';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        router(function() {
            route(method(GET), url_path_params('/users/:id'), function() {
                $id = $_GET[':id'];
                json_out(['user_id' => $id]);
            });
        });

        $this->expectOutputString('{"user_id":"123"}');
    }
}
```

## Testing Middleware

```php
<?php

use PHPUnit\Framework\TestCase;

class AuthMiddlewareTest extends TestCase
{
    public function test_unauthorized_without_token()
    {
        $_GET['url'] = 'protected';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_AUTHORIZATION'] = '';  // No token

        router(function() {
            route(method(GET), url_path('/protected'), middleware(
                function() {
                    if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
                        render(401, json_out(['error' => 'Unauthorized']));
                        stop();
                    }
                },
                function() {
                    render(200, json_out(['data' => 'secret']));
                }
            ));
        });

        $this->expectOutputString('{"error":"Unauthorized"}');
        $this->assertEquals(401, http_response_code());
    }

    public function test_authorized_with_token()
    {
        $_GET['url'] = 'protected';
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_AUTHORIZATION'] = 'Bearer valid-token';

        router(function() {
            route(method(GET), url_path('/protected'), middleware(
                function() {
                    if (empty($_SERVER['HTTP_AUTHORIZATION'])) {
                        render(401, json_out(['error' => 'Unauthorized']));
                        stop();
                    }
                },
                function() {
                    render(200, json_out(['data' => 'secret']));
                }
            ));
        });

        $this->expectOutputString('{"data":"secret"}');
        $this->assertEquals(200, http_response_code());
    }
}
```

## Testing Error Handling

```php
<?php

use PHPUnit\Framework\TestCase;
use TeensyPHP\Exceptions\TeensyPHPException;

class ErrorHandlingTest extends TestCase
{
    public function test_not_found_returns_404()
    {
        $_GET['url'] = 'nonexistent';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        router(function() {
            route(method(GET), url_path('/nonexistent'), function() {
                TeensyPHPException::throwNotFound();
            });
        });

        $this->assertEquals(404, http_response_code());
    }
}
```

## Test Setup Pattern

```php
<?php

use PHPUnit\Framework\TestCase;

class BaseApiTest extends TestCase
{
    protected function setUp(): void
    {
        // Reset superglobals before each test
        $_GET = [];
        $_POST = [];
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['HTTP_AUTHORIZATION'] = '';

        // Reset response code
        http_response_code(200);
    }

    protected function tearDown(): void
    {
        // Clean up after each test
        $_GET = [];
        $_POST = [];
    }

    protected function simulateRequest(string $method, string $url, array $headers = []): void
    {
        $_GET['url'] = ltrim($url, '/');
        $_SERVER['REQUEST_METHOD'] = $method;

        foreach ($headers as $key => $value) {
            $_SERVER['HTTP_' . strtoupper(str_replace('-', '_', $key))] = $value;
        }
    }
}

class MyApiTest extends BaseApiTest
{
    public function test_home_route()
    {
        $this->simulateRequest('GET', '/');

        router(function() {
            route(method(GET), url_path('/'), function() {
                json_out(['message' => 'Hello']);
            });
        });

        $this->expectOutputString('{"message":"Hello"}');
    }
}
```

## How Testing Works

TeensyPHP is designed for testability:

1. **Use `stop()` instead of `exit()`** - The `stop()` function allows tests to continue after a route handler finishes, while `exit()` would terminate the entire test process.

2. **Simulate requests via superglobals** - Set `$_GET['url']` and `$_SERVER['REQUEST_METHOD']` to simulate different requests.

3. **Capture output** - Use PHPUnit's `expectOutputString()` to verify response content.

4. **Check status codes** - Use `http_response_code()` to verify the HTTP status.

## Testing Checklist

| Test Type | What to Test |
|-----------|--------------|
| Success paths | Correct response for valid input |
| Error paths | Proper error codes and messages |
| Authentication | Protected routes reject unauthorized requests |
| Validation | Invalid input returns appropriate errors |
| Edge cases | Empty data, missing fields, boundary values |

## Running Tests

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test file
./vendor/bin/phpunit tests/ApiTest.php

# Run with coverage
./vendor/bin/phpunit --coverage-html coverage
```
