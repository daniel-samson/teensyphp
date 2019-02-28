# Teensy PHP

The minimalists web framework

### Features
- Minimal cognitive load (easy to read)
- Minimal learning curve (easy to find)
- The fastest (no classes, namespaces to load)
- The smallest (< 1000 LLOC)
- Strongly typed
- Avoid object oriented programming encapsulation
- Favor arrays over objects
- Favor iterators and generators
- Favor vanilla php over a library
- Split out methods into separate concerns
- Easy to inject or replace functionality
- Framework agnostic

### Routing

```php
<?php
// index.php

route(method(GET), url_path('/'), function() { 
    render(200, content(JSON_CONTENT, json_encode(["hello" => "world"]))); 
});

route(method(POST), url_path('/'), function() {
    $json = json_in();
    // ...
    render(200, json_out([])); 
});

// other wise
render(404, json_out([]));

```