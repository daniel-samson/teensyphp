---
sidebar_position: 2
---

# Routing

TeensyPHP provides a lightweight router to manage application endpoints.

## Route Function

```php
route(bool $method, bool $path, callable|string $action)
```

## Router Groups

Group URL paths together with a shared prefix:

```php
routerGroup(string $pathPrefix, callable $action)
```

## HTTP Method Filtering

Use the `method` predicate to filter HTTP methods:

```php
method(GET);
method(POST);
method(PUT);
method(PATCH);
method(DELETE);
method(HEAD);
method(CONNECT);
method(OPTIONS);
method(TRACE);
```

## URL Filtering

### Simple URLs

```php
url_path("/contact-us")
```

### URLs with Parameters

```php
url_path_params('/orders/from/:from_date/to/:to_date')
```

Access parameters via `$_GET`:

```php
$from_date = $_GET[':from_date'];
$to_date = $_GET[':to_date'];
```

## Callable Actions

```php
route(method(GET), url_path('/contact-us'), function() {
    // do something
});
```

Using router groups:

```php
routerGroup("/api", function() {
    route(method(GET), url_path('/info'), function() {
        // do something
    });
});
```

## Single Action Controllers

```php
class ListPosts
{
    public function __invoke()
    {
        render(200, json_out(["message" => "hello world"]));
    }
}

route(method(GET), url_path('/posts'), ListPosts::class);
```
