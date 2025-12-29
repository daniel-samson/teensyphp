---
sidebar_position: 1
---

# Laravel Valet

## Quick Setup

```php
// public/index.php
router(function() {
    use_request_uri();  // Enable Valet compatibility

    route(method(GET), url_path("/"), function() {
        json_out(["message" => "Hello from Valet!"]);
    });

    route(method(GET), url_path("/api/users"), function() {
        json_out(["users" => []]);
    });
});
```

Then link your project:

```bash
cd your-project
valet link myapp
```

Your app is now available at `http://myapp.test`.

## How It Works

Laravel Valet uses its own routing mechanism that doesn't rely on `.htaccess` mod-rewrite rules. The `use_request_uri()` function tells TeensyPHP to read the route from `$_SERVER['REQUEST_URI']` instead of the `url` query parameter.

Without `use_request_uri()`, TeensyPHP expects URLs like:
```
http://myapp.test/index.php?url=api/users
```

With `use_request_uri()`, TeensyPHP handles clean URLs:
```
http://myapp.test/api/users
```

## Function Reference

### use_request_uri()

```php
use_request_uri(): void
```

Configures the router to use `$_SERVER['REQUEST_URI']` for route matching instead of `$_GET['url']`.

| When to Use | Description |
|-------------|-------------|
| Laravel Valet | Local development with Valet |
| PHP built-in server | `php -S localhost:8000` |
| Servers without mod-rewrite | When you can't use `.htaccess` |
