# Teensy PHP
[![Build Status](https://travis-ci.org/daniel-samson/teensyphp.svg?branch=master)](https://travis-ci.org/daniel-samson/teensyphp)
[![codecov](https://codecov.io/gh/daniel-samson/teensyphp/branch/master/graph/badge.svg)](https://codecov.io/gh/daniel-samson/teensyphp)

A minimalistic web framework for rapidly creating JSON APIs and web applications, written with the fewest lines of code.

## Features
- Simple Router
- PHP Templating Engine
- Middlewhere Support
- Easy to inject or replace functionality (its just some small functions)

Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.


## Example
```php
// index.php
<?php
require_once __DIR__ . '/vendor/autoload.php';
display_errors(false);
error_reporting(E_ALL);

try {
    // home / landing page
    route(method(GET), url_path("/"), function () {
        render(200, json_out(['hello' => 'world']));
    });

    route(method(POST), url_path("/authenticate"), function() {
        $body = json_in();
        if (Foo::authenticate($body['username'], $body['password'])) {
            render(200, json_out(["access_token"=> Foo::accessToken(),
                "token_type"=> "bearer",
                "expires_in"=> 3600,
                "scope"=> "create"]));
        }

        render(400, json_out(["status" => "bad request"]));
    });

    // route not found
    render(404, json_out(['error' => 'not found']));
} catch (Exception $e) {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    render(500, json_out(['error' => 'internal server error']));
} catch (Error $e) {
    error_log($e->getMessage());
    error_log($e->getTraceAsString());
    render(500, json_out(['error' => 'internal server error'])));
}
 
```
