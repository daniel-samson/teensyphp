---
sidebar_position: 1
---

# Laravel Valet

To configure your app to work with Laravel Valet, call `use_request_uri` at the start of your router:

```php
router(function() {
    use_request_uri();
    // ... add the routes here
});
```

This uses the request URI to determine the route instead of using mod-rewrite rules.
