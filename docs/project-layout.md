---
sidebar_position: 2
---

# Project Layout

When you create a new TeensyPHP project, it has the following structure:

```
my-app/
├── App/
│   ├── Actions/           # Controller actions (invokable classes)
│   │   └── Home/
│   │       └── DisplayHome.php
│   ├── Entity/            # Database entities
│   │   └── BaseEntity.php
│   └── Requests/          # Request validation classes
├── public/                # Web root (point your server here)
│   ├── index.php          # Entry point
│   └── css/
│       └── base.css
├── routes/                # Route definitions
│   ├── web.php            # Web routes
│   └── api.php            # API routes
├── templates/             # View templates
│   ├── components/        # Reusable components
│   │   └── table.php
│   ├── layouts/           # Page layouts
│   │   ├── page_beginning.php
│   │   └── page_end.php
│   └── pages/             # Page templates
│       ├── home.php
│       ├── 404.php
│       └── 500.php
├── .env                   # Environment configuration (create from .env.example)
├── .env.example           # Example environment file
├── bootstrap.php          # Application bootstrap
├── composer.json          # Dependencies
├── functions.php          # Helper functions
└── globals.php            # Global constants
```

## Key Files

### public/index.php

The entry point for all requests. It simply loads the bootstrap file:

```php
<?php
require_once dirname(__DIR__) . "/bootstrap.php";
```

### bootstrap.php

Initializes the application: loads dependencies, configures the database, sets up logging, and loads routes:

```php
<?php
use TeensyPHP\Utility\Config;
use TeensyPHP\Utility\Database;
use App\Entity\BaseEntity;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/globals.php";
require_once __DIR__ . "/functions.php";

// Load environment configuration
Config::loadEnvFile(app_root());

// Initialize database
BaseEntity::$DB = Database::connect(
    Config::get("DATABASE_ENGINE", "sqlite"),
    Config::get("DATABASE_DATABASE", __DIR__ . "/teensydb.sqlite"),
    Config::get("DATABASE_HOST"),
    Config::get("DATABASE_PORT"),
    Config::get("DATABASE_USERNAME"),
    Config::get("DATABASE_PASSWORD"),
)->connection();

// Load and run routes
router(function() {
    use_request_uri();
    require_once app_root() . "/routes/web.php";
    require_once app_root() . "/routes/api.php";
});
```

### globals.php

Defines global constants used throughout the application:

```php
<?php
!defined('APP_ROOT') && define('APP_ROOT', __DIR__);
```

Use `app_root()` to access the application root directory in your code.

### functions.php

Custom helper functions for the application:

```php
<?php
function handle_exception(Throwable $exception): void
{
    // Log the error
    $prefix = "[" . date("Y-m-d H:i:s") . "] ";
    file_put_contents(__DIR__ . "/error.log", $prefix . $exception->getMessage() . PHP_EOL, FILE_APPEND);

    // Display error page
    if ($exception->getCode() === 404) {
        http_response_code(404);
        echo template(__DIR__ . "/templates/pages/404.php", []);
    } else {
        http_response_code(500);
        echo template(__DIR__ . "/templates/pages/500.php", []);
    }
}

function no_php_headers(): void
{
    header_remove("X-Powered-By");
    header_remove("Server");
}

function start_session(): void
{
    if (!session_id()) {
        session_name("SID");
        session_start();
    }
}
```

## Directory Purposes

### App/Actions/

Invokable controller classes organized by feature. Each action handles a single route:

```php
// App/Actions/Home/DisplayHome.php
namespace App\Actions\Home;

class DisplayHome
{
    public function __invoke()
    {
        render(200, html_out(template(app_root() . "/templates/pages/home.php", [])));
    }
}
```

### App/Entity/

Database entity classes that extend BaseEntity:

```php
// App/Entity/User.php
namespace App\Entity;

class User extends BaseEntity
{
    public static string $table = 'users';
    public int $id;
    public string $name;
    public string $email;
}
```

### App/Requests/

Request validation classes (optional, for validating incoming data).

### routes/

Route definitions split by type:

```php
// routes/web.php - HTML page routes
use App\Actions\Home\DisplayHome;

routerGroup("/", function() {
    route(method(GET), url_path("/"), DisplayHome::class);
});

// routes/api.php - API routes
routerGroup("/api", function() {
    route(method(GET), url_path("/"), function() {
        render(200, json_out(["message" => "Hello World!"]));
    });
});
```

### templates/

PHP templates organized into subdirectories:

| Directory | Purpose |
|-----------|---------|
| `templates/layouts/` | Page wrappers (header, footer) |
| `templates/pages/` | Full page templates |
| `templates/components/` | Reusable UI components |

```php
// templates/pages/home.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Home"]); ?>
<h1>Welcome Home</h1>
<p>This is a TeensyPHP project.</p>
<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

## Summary

| File/Directory | Purpose |
|----------------|---------|
| `public/index.php` | Entry point - loads bootstrap.php |
| `bootstrap.php` | Initializes database, logging, session, and routes |
| `globals.php` | Defines APP_ROOT and other constants |
| `functions.php` | Custom helper functions |
| `routes/web.php` | Web page routes |
| `routes/api.php` | API routes |
| `App/Actions/` | Invokable controller classes |
| `App/Entity/` | Database entity classes |
| `templates/` | PHP templates for HTML rendering |
