---
sidebar_position: 4
---

# Middleware

TeensyPHP provides the `middleware` method to execute callables in order, allowing you to inject features that control your application flow.

```php
middleware(callable|string ...$method)
```

## Class-Based Middleware

```php
class Authorization
{
    public function __invoke()
    {
        if (empty(request_header('Authorization'))) {
            render(403, json_out([]));
            stop();
        }
        // TODO: decode the header
    }
}
```

Use in routes:

```php
route(method(GET), url_path("/"), middleware(Authorization::class, function() {
    render(200, json_out(["hello" => "world"]));
}));
```

## Function-Based Middleware

```php
// authorization.php
function authorization(): callable {
    return function() {
        if (empty(request_header('Authorization'))) {
            render(403, json_out([]));
            stop();
        }
    };
}
```

Use in routes:

```php
route(method(GET), url_path("/"), middleware(authorization(), function() {
    render(200, json_out(["hello" => "world"]));
}));
```
