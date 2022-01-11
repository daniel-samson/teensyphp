# Teensy PHP

Teensy PHP is a minimalistic web framework for rapidly creating JSON APIs.

## Project Status
[![Build](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml/badge.svg)](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/daniel-samson/teensyphp/branch/master/graph/badge.svg)](https://codecov.io/gh/daniel-samson/teensyphp)




## Features
- Lightweight Framework
- Simple Router
- PHP Templating Engine
- Middlewhere Support
- Easy to inject or replace functionality (its just some small functions)

## Requirements
- PHP 7.2+
- Web server with friendly url capability eg. Apache, NGINX, ect...
- Composer


## Example
```php
// index.php
<?php
require_once __DIR__ . '/vendor/autoload.php';

router(function() {
    // home / landing page
    route(method(GET), url_path("/"), function () {
        render(200, json_out(['status' => 'up']));
    });
    
    // Add your endpoints / routes here ...
    route(method(GET), url_path_params("/hello/:name"), function () {
        render(200, json_out(['hello' => $_GET[':name']]));
    })
    
    route(method(POST), url_path("/hello"), function () {
        $body = json_in();
        render(201, json_out($body));
    })

    // route not found
    throw new \Error("Not Found", 404);
});
```

## Installation

```bash
composer require daniel-samson/teensyphp
``` 

## Documentation
Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.
