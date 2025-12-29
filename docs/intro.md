---
sidebar_position: 1
---

# Getting Started

TeensyPHP is a micro web framework for rapidly creating REST APIs and hypermedia applications.

## Requirements

- PHP 8.0 or higher
- Composer

## Installation

### Install TeensyPHP CLI

```bash
composer global require daniel-samson/teensyphp
```

### Update TeensyPHP CLI

```bash
composer global update
```

### Create a New Project

```bash
teensyphp new project-name
```

## Begin

```bash
cd project-name
composer install
```

### Configure Database

Edit the `.env` file with your database credentials.

### Start Development Server

```bash
composer dev
```

- Web route: [http://localhost:8000](http://localhost:8000)
- API route: [http://localhost:8000/api](http://localhost:8000/api)

## Add to Existing Project

### Install Package

```bash
composer require daniel-samson/teensyphp
```

### Update Entry Point

Add the router to your entry point file:

```php
// index.php
router(function() {
    route(method(GET), url_path("/"), function() {
        json_out(["hello" => "world"]);
    });
    // add your routes here
});
```
