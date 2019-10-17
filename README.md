# Teensy PHP

Teesy PHP is a minimalistic web framework for rapidly creating JSON APIs and microservices. Unlike other mininal frameworks, TeensyPHP is implemented in a functional style. Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.

[![Build Status](https://travis-ci.org/daniel-samson/teensyphp.svg?branch=master)](https://travis-ci.org/daniel-samson/teensyphp)
[![codecov](https://codecov.io/gh/daniel-samson/teensyphp/branch/master/graph/badge.svg)](https://codecov.io/gh/daniel-samson/teensyphp)




## Features
- Simple Router
- PHP Templating Engine
- Middlewhere Support
- Easy to inject or replace functionality (its just some small functions)


## Example
```php
// index.php
<?php
require_once __DIR__ . '/vendor/autoload.php';
ini_set('display_errors', 'Off');
error_reporting(E_ALL);

try {
    // home / landing page
    route(method(GET), url_path("/"), function () {
        render(200, json_out(['status' => 'up']));
    });
    
    // Add your endpoints / routes here ...
    
    // route not found
    render(404, json_out(['error' => 'not found']));
} catch (Exception $e) {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    render($e->getCode(), json_out(['error' => $e->getMessage()]));
} catch (Error $e) {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    render($e->getCode(), json_out(['error' => $e->getMessage()]));
}
 
```
