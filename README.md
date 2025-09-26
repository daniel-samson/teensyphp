# Teensy PHP

Teensy PHP is a micro web framework for rapidly creating REST APIs and hypermedia.

## Project Status
[![Build](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml/badge.svg)](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/daniel-samson/teensyphp/branch/main/graph/badge.svg?token=oJv9JF2p1J)](https://codecov.io/gh/daniel-samson/teensyphp)
[![CodeFactor](https://www.codefactor.io/repository/github/daniel-samson/teensyphp/badge)](https://www.codefactor.io/repository/github/daniel-samson/teensyphp)


## Features
- Lightweight Framework
- Simple Router
- PHP Templating Engine
- Middleware Support
- Easy to inject or replace functionality (its just some small functions)
- env Config Utility
- Database Connection Manager Utility
- ArrayAccessImplementation, Crud Traits


## Examples
```php

<?php
// routes/web.php
use App\Actions\Home\DisplayHome;

routerGroup("/", function () {
    // Homepage
    route(method(GET), url_path('/'), DisplayHome::class);

    // Example url parameter
    route(method(GET), url_path_params("/hello/:name"), function () {
        render(200, html_out(template('src/templates/hello.php', ['name' => $_GET[':name']])));
    });
});
```

```php
<?php
// routes/api.php

// /api/{route}
routerGroup("/api", function () {
    // API home
    route(method("GET"), url_path("/"), function () {
        render(200, json_out(["message" => "Hello World!"]));
    });

    // Example JSON body (echo server)
    route(method(POST), url_path("/echo"), function () {
        $body = json_in();
        render(201, json_out($body));
    });
});
```

```php
<?php
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
            render(200, html_out(template(APP_ROOT . "/templates/pages/home.php", [])));
        }
    }
}
```

## Requirements
- PHP 8.0+
- Composer

## Documentation
Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information on how to rapidly create apps with teensyphp.

## Creating a new project

### Install teensyphp command line tool

```shell
composer global require daniel-samson/teensyphp
```

### Create a new project

```shell
teensyphp new project-name
cd project-name
composer install
```

### Start buit in web server

```shell
composer dev
```

- open web route [http://localhost:8000](http://localhost:8000)
- open api route [http://localhost:8000/api](http://localhost:8000/api)

## Update Teensyphp

```shell
composer global update
```
