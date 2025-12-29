---
sidebar_position: 5
---

# Templating

TeensyPHP provides the `template` function to render PHP-based templates, components, and mixins.

```php
template(string $path, array $variables)
```

## Basic Template

```php
// homepage.php
<h1>Welcome Home <?= $username ?></h1>
```

Using the template:

```php
route(method(GET), url_path("/"), function() {
    render(200, html_out(template('homepage.php', ['username' => $_SESSION['username']])));
});
```

## Components & Mixins

Create reusable template segments:

### CSS Component

```php
// components/css.php
<link rel="stylesheet" href="/css/style.css">
```

### Page Header

```php
// components/page_header.php
<!DOCTYPE html>
<html>
<head>
<title>My site - <?= $title ?></title>
<?= template(__DIR__.'/css.php', []) ?>
</head>
<body>
```

### Page Footer

```php
// components/page_footer.php
</body>
</html>
```

### Combined Template

```php
// homepage.php
<?= template(__DIR__.'/components/page_header.php', ['title' => 'Welcome Home']) ?>
<h1>Hello World</h1>
<?= template(__DIR__.'/components/page_footer.php', []) ?>
```

## Non-HTML Templates

Templates work with any file type:

```php
// components/json_error.php
{
  "errors": {
    "code": <?= json_encode($code) ?>,
    "title": <?= json_encode($message) ?>
  }
}
```

Usage:

```php
try {
    // something
} catch (Exception $e) {
    render(500, content(JSON_CONTENT, template(__DIR__.'/components/json_error.php', [
        'code' => $e->getCode(),
        'message' => $e->getMessage()
    ])));
}
```
