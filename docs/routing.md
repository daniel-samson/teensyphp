---
sidebar_position: 3
---

# Routing

## Basic Routes

```php
// routes/web.php
routerGroup("/", function() {
    route(method(GET), url_path("/"), function() {
        render(200, html_out(template(app_root() . "/templates/pages/home.php", [])));
    });

    route(method(GET), url_path("/about"), function() {
        render(200, html_out(template(app_root() . "/templates/pages/about.php", [])));
    });
});

// routes/api.php
routerGroup("/api", function() {
    route(method(GET), url_path("/"), function() {
        render(200, json_out(["message" => "Hello World!"]));
    });

    route(method(POST), url_path("/users"), function() {
        $data = json_in();
        render(201, json_out(["created" => $data]));
    });

    route(method(PUT), url_path_params("/users/:id"), function() {
        $id = $_GET[':id'];
        $data = json_in();
        render(200, json_out(["updated" => $id, "data" => $data]));
    });

    route(method(DELETE), url_path_params("/users/:id"), function() {
        $id = $_GET[':id'];
        render(204, "");
    });
});
```

## Route Files

TeensyPHP projects separate routes into files:

```php
// bootstrap.php (loads route files)
router(function() {
    use_request_uri();
    require_once app_root() . "/routes/web.php";
    require_once app_root() . "/routes/api.php";
});
```

## URL Parameters

```php
// routes/api.php
routerGroup("/api", function() {
    // Single parameter: GET /api/users/123
    route(method(GET), url_path_params("/users/:id"), function() {
        $userId = $_GET[':id'];
        render(200, json_out(["user_id" => $userId]));
    });

    // Multiple parameters: GET /api/orders/from/2024-01-01/to/2024-12-31
    route(method(GET), url_path_params("/orders/from/:from_date/to/:to_date"), function() {
        $from = $_GET[':from_date'];
        $to = $_GET[':to_date'];
        render(200, json_out(["from" => $from, "to" => $to]));
    });

    // Mixed with query strings: GET /api/products/electronics?sort=price
    route(method(GET), url_path_params("/products/:category"), function() {
        $category = $_GET[':category'];
        $sort = $_GET['sort'] ?? 'name';
        render(200, json_out(["category" => $category, "sort" => $sort]));
    });
});
```

## Action Classes

```php
// App/Actions/Post/ListPosts.php
namespace App\Actions\Post;

use App\Entity\Post;

class ListPosts
{
    public function __invoke()
    {
        $posts = Post::findAll();
        $output = array_map(fn($p) => $p->toArray(), $posts);
        render(200, json_out(["posts" => $output]));
    }
}

// App/Actions/Post/CreatePost.php
namespace App\Actions\Post;

use App\Entity\Post;

class CreatePost
{
    public function __invoke()
    {
        $data = json_in();
        $post = Post::create($data);
        render(201, json_out(["post" => $post->toArray()]));
    }
}

// App/Actions/Post/ShowPost.php
namespace App\Actions\Post;

use App\Entity\Post;
use TeensyPHP\Exceptions\TeensyPHPException;

class ShowPost
{
    public function __invoke()
    {
        $post = Post::find($_GET[':id']);
        if (!$post) {
            TeensyPHPException::throwNotFound();
        }
        render(200, json_out(["post" => $post->toArray()]));
    }
}
```

```php
// routes/api.php
use App\Actions\Post\ListPosts;
use App\Actions\Post\CreatePost;
use App\Actions\Post\ShowPost;

routerGroup("/api", function() {
    route(method(GET), url_path("/posts"), ListPosts::class);
    route(method(POST), url_path("/posts"), CreatePost::class);
    route(method(GET), url_path_params("/posts/:id"), ShowPost::class);
});
```

## Web Routes with Templates

```php
// App/Actions/Home/DisplayHome.php
namespace App\Actions\Home;

class DisplayHome
{
    public function __invoke()
    {
        $accept = request_header('Accept');
        if ($accept === 'application/json') {
            render(200, json_out(['message' => 'Hello World']));
        } else {
            render(200, html_out(template(app_root() . "/templates/pages/home.php", [])));
        }
    }
}

// routes/web.php
use App\Actions\Home\DisplayHome;

routerGroup("/", function() {
    route(method(GET), url_path("/"), DisplayHome::class);
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

### app_root()

```php
app_root(): string
```

Returns the application root directory (defined in `globals.php` as `APP_ROOT`).

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
