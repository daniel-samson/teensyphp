# Teensy PHP

A minimalistic web framework for rapidly creating JSON APIs and web applications. Teensy PHP attempts to use composition with functions instead of classes.

## Features
- Type hinting
- Easy to inject or replace functionality (its just functions)


## Example
```php
route(method(GET), url_path("/"), function() {
    render(200, json_out(["hello" => "world"]));
});
```

Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.
