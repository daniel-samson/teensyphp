# Teensy PHP

A minimalistic web framework for those who prefer the procedural PHP style over the object oriented style. Teensy PHP is an attempt to use composition with functions instead of classes. With less than 100 Logical Lines of Code, you can rapidly create JSON APIs and web application.

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
