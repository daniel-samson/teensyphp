# Teensy PHP

Teensy PHP is a minimal web framework for rapidly creating REST APIs.

## Project Status
[![Build](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml/badge.svg)](https://github.com/daniel-samson/teensyphp/actions/workflows/php.yml)
[![codecov](https://codecov.io/gh/daniel-samson/teensyphp/branch/main/graph/badge.svg?token=oJv9JF2p1J)](https://codecov.io/gh/daniel-samson/teensyphp)



## Features
- Lightweight Framework
- Simple Router
- PHP Templating Engine
- Middleware Support
- Easy to inject or replace functionality (its just some small functions)


## Example
```php
// index.php
<?php
require_once __DIR__ . '/vendor/autoload.php';

router(function() {
    // uncomment when using laravel valet:
    // use_request_uri();
    //
    // healthcheck
    route(method(GET), url_path("/"), fn () => render(200, json_out(['status' => 'up'])));
    
    // Example url parameter
    route(method(GET), url_path_params("/hello/:name"), function () {
        render(200, json_out(['hello' => $_GET[':name']]));
    });
    
    // Example JSON body
    route(method(POST), url_path("/hello"), function () {
        $body = json_in();
        render(201, json_out($body));
    });
});
```
## Requirements
- PHP 7.2+
- Composer

## Installation

### Create a new project
```bash
mkdir new-project
cd new-project
composer init
```


### Get teensyphp
```bash
composer require daniel-samson/teensyphp
``` 

## Documentation
Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.
