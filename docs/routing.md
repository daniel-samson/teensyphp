---
sidebar_position: 2
---

# Routing

## Basic Routes

```php
router(function() {
    // GET request to /
    route(method(GET), url_path("/"), function() {
        json_out(["message" => "Welcome!"]);
    });

    // POST request to /users
    route(method(POST), url_path("/users"), function() {
        $data = json_in();
        json_out(["created" => $data]);
    });

    // PUT request to /users/123
    route(method(PUT), url_path_params("/users/:id"), function() {
        $id = $_GET[':id'];
        $data = json_in();
        json_out(["updated" => $id, "data" => $data]);
    });

    // DELETE request to /users/123
    route(method(DELETE), url_path_params("/users/:id"), function() {
        $id = $_GET[':id'];
        json_out(["deleted" => $id]);
    });
});
```

## Grouped Routes

```php
router(function() {
    // All routes prefixed with /api
    routerGroup("/api", function() {
        // GET /api/users
        route(method(GET), url_path("/users"), function() {
            json_out(["users" => []]);
        });

        // GET /api/posts
        route(method(GET), url_path("/posts"), function() {
            json_out(["posts" => []]);
        });
    });

    // Nested groups
    routerGroup("/admin", function() {
        routerGroup("/api", function() {
            // GET /admin/api/stats
            route(method(GET), url_path("/stats"), function() {
                json_out(["stats" => []]);
            });
        });
    });
});
```

## URL Parameters

```php
router(function() {
    // Single parameter
    route(method(GET), url_path_params("/users/:id"), function() {
        $userId = $_GET[':id'];
        json_out(["user_id" => $userId]);
    });

    // Multiple parameters
    route(method(GET), url_path_params("/orders/from/:from_date/to/:to_date"), function() {
        $from = $_GET[':from_date'];
        $to = $_GET[':to_date'];
        json_out(["from" => $from, "to" => $to]);
    });

    // Mixed with query strings
    // GET /products/electronics?sort=price
    route(method(GET), url_path_params("/products/:category"), function() {
        $category = $_GET[':category'];
        $sort = $_GET['sort'] ?? 'name';
        json_out(["category" => $category, "sort" => $sort]);
    });
});
```

## Controller Classes

```php
// ListPosts.php
class ListPosts
{
    public function __invoke()
    {
        $posts = Post::findAll();
        render(200, json_out(["posts" => $posts]));
    }
}

// CreatePost.php
class CreatePost
{
    public function __invoke()
    {
        $data = json_in();
        $post = Post::create($data);
        render(201, json_out(["post" => $post->toArray()]));
    }
}

// routes.php
router(function() {
    route(method(GET), url_path("/posts"), ListPosts::class);
    route(method(POST), url_path("/posts"), CreatePost::class);
});
```

## How Routing Works

The `route()` function matches incoming requests against defined routes. When both the HTTP method and URL path match, the associated action (callback or controller) is executed.

Routes are evaluated in order, and the first match wins. The router stops after finding a match.

## Function Reference

### route()

```php
route(bool $method, bool $path, callable|string $action): void
```

Defines a route that matches when both method and path predicates return true.

### routerGroup()

```php
routerGroup(string $pathPrefix, callable $action): void
```

Groups routes under a common URL prefix. Groups can be nested.

### method()

```php
method(string $httpMethod): bool
```

Returns true if the current request matches the specified HTTP method.

### url_path()

```php
url_path(string $path): bool
```

Returns true if the current request URL matches the specified path exactly.

### url_path_params()

```php
url_path_params(string $pattern): bool
```

Returns true if the URL matches the pattern. Captures named parameters (prefixed with `:`) into `$_GET`.

## HTTP Methods

| Constant | HTTP Method | Typical Use |
|----------|-------------|-------------|
| `GET` | GET | Retrieve resources |
| `POST` | POST | Create resources |
| `PUT` | PUT | Replace resources |
| `PATCH` | PATCH | Partial updates |
| `DELETE` | DELETE | Remove resources |
| `HEAD` | HEAD | Headers only |
| `OPTIONS` | OPTIONS | CORS preflight |
| `CONNECT` | CONNECT | Tunnel connections |
| `TRACE` | TRACE | Debugging |
