# Teensy PHP

A minimalistic web framework for rapidly creating JSON APIs and web applications written with the fewest lines of code.

## Features
- Router
- Templating in php
- Middlewhere
- Easy to inject or replace functionality (its just some small functions)


## Example
```php
route(method(GET), url_path("/"), function() {
    render(200, json_out(["hello" => "world"]));
});
```

Please see the [wiki](https://github.com/daniel-samson/teensyphp/wiki) for more information.
