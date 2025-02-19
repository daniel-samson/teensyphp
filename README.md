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


## Example
```php
// routes/index.php
<?php
require_once __DIR__ . '/vendor/autoload.php';

router(function() {
    // uncomment when using laravel valet/herd or when mod_rewrite is unavailable:
    // use_request_uri();
    //
    // healthcheck
    route(method(GET), url_path("/"), fn () => render(200, json_out(['status' => 'up'])));
    
    // Example url parameter
    route(method(GET), url_path_params("/hello/:name"), function () {
        render(200, html_out(template('src/templates/hello.php', ['name' => $_GET[':name']])));
    });
    
    // Example JSON body (echo server)
    route(method(POST), url_path("/echo"), function () {
        $body = json_in();
        render(201, json_out($body));
    });
});
```
## Requirements
- PHP 7.2+
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
```
