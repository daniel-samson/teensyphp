---
sidebar_position: 1
---

# Getting Started

## Quick Start

```bash
# Install TeensyPHP CLI globally
composer global require daniel-samson/teensyphp

# Create a new project
teensyphp new my-app

# Navigate to project and install dependencies
cd my-app
composer install

# Start the development server
composer dev
```

Your app is now running:
- Web route: [http://localhost:8000](http://localhost:8000)
- API route: [http://localhost:8000/api](http://localhost:8000/api)

## Add to Existing Project

```php
// 1. Install the package
// composer require daniel-samson/teensyphp

// 2. Add the router to your entry point (index.php)
router(function() {
    route(method(GET), url_path("/"), function() {
        json_out(["hello" => "world"]);
    });

    route(method(GET), url_path("/users"), function() {
        json_out(["users" => ["Alice", "Bob", "Charlie"]]);
    });

    route(method(POST), url_path("/users"), function() {
        $data = json_in();
        json_out(["created" => $data]);
    });
});
```

## What is TeensyPHP?

TeensyPHP is a micro web framework for rapidly creating REST APIs and hypermedia applications. It provides a minimal set of functions for routing, request/response handling, templating, and database operations without the overhead of larger frameworks.

## Requirements

| Requirement | Version |
|-------------|---------|
| PHP | 8.0 or higher |
| Composer | Latest |

## CLI Commands

| Command | Description |
|---------|-------------|
| `teensyphp new <project-name>` | Create a new TeensyPHP project |
| `composer global update` | Update TeensyPHP CLI to latest version |
| `composer dev` | Start the development server |

## Configuration

Edit the `.env` file in your project root to configure your database and other settings:

```ini
# Database Configuration
DATABASE_ENGINE=mysql
DATABASE_DATABASE=myapp
DATABASE_USERNAME=root
DATABASE_PASSWORD=secret
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306

# For SQLite (path relative to public/)
# DATABASE_ENGINE=sqlite
# DATABASE_DATABASE=../myapp.sqlite

# Logging
LOG_LEVEL=debug
```
