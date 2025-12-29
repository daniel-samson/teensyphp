---
sidebar_position: 5
---

# Templating

## Basic Template

```php
// templates/greeting.php
<h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
<p>Welcome to our site.</p>
```

```php
// Using the template
router(function() {
    route(method(GET), url_path("/"), function() {
        $html = template(__DIR__ . '/templates/greeting.php', [
            'name' => 'Alice'
        ]);
        render(200, html_out($html));
    });
});
```

**Output:**
```html
<h1>Hello, Alice!</h1>
<p>Welcome to our site.</p>
```

## Page Layout with Components

```php
// templates/components/header.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - My Site</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav>
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/contact">Contact</a>
    </nav>
    <main>
```

```php
// templates/components/footer.php
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> My Site</p>
    </footer>
</body>
</html>
```

```php
// templates/home.php
<?= template(__DIR__ . '/components/header.php', ['title' => 'Home']) ?>

<h1>Welcome Home</h1>
<p>This is the homepage content.</p>

<?= template(__DIR__ . '/components/footer.php', []) ?>
```

```php
// Using the page template
router(function() {
    route(method(GET), url_path("/"), function() {
        render(200, html_out(template(__DIR__ . '/templates/home.php', [])));
    });
});
```

## Dynamic Lists

```php
// templates/user-list.php
<ul class="users">
<?php foreach ($users as $user): ?>
    <li>
        <strong><?= htmlspecialchars($user['name']) ?></strong>
        <span><?= htmlspecialchars($user['email']) ?></span>
    </li>
<?php endforeach; ?>
</ul>
```

```php
router(function() {
    route(method(GET), url_path("/users"), function() {
        $users = [
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
        ];

        $html = template(__DIR__ . '/templates/user-list.php', [
            'users' => $users
        ]);
        render(200, html_out($html));
    });
});
```

## JSON Templates

```php
// templates/api-error.php
{
    "error": {
        "code": <?= json_encode($code) ?>,
        "message": <?= json_encode($message) ?>,
        "details": <?= json_encode($details) ?>
    }
}
```

```php
router(function() {
    route(method(GET), url_path("/api/resource"), function() {
        try {
            // Something that might fail
            throw new Exception("Resource not found");
        } catch (Exception $e) {
            $json = template(__DIR__ . '/templates/api-error.php', [
                'code' => 404,
                'message' => $e->getMessage(),
                'details' => null
            ]);
            render(404, content(JSON_CONTENT, $json));
        }
    });
});
```

## Conditional Content

```php
// templates/dashboard.php
<?= template(__DIR__ . '/components/header.php', ['title' => 'Dashboard']) ?>

<?php if ($isAdmin): ?>
    <div class="admin-panel">
        <h2>Admin Controls</h2>
        <a href="/admin/users">Manage Users</a>
    </div>
<?php endif; ?>

<div class="user-content">
    <h1>Welcome, <?= htmlspecialchars($username) ?></h1>

    <?php if (empty($notifications)): ?>
        <p>No new notifications.</p>
    <?php else: ?>
        <ul class="notifications">
        <?php foreach ($notifications as $note): ?>
            <li><?= htmlspecialchars($note) ?></li>
        <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?= template(__DIR__ . '/components/footer.php', []) ?>
```

## How Templating Works

The `template()` function loads a PHP file and executes it with the provided variables in scope. The output is captured and returned as a string.

Templates are just PHP files, so you have full access to:
- PHP control structures (if, foreach, while)
- PHP functions
- Nested template calls
- Any PHP code

Variables passed in the second argument are extracted into the template's scope.

## Function Reference

### template()

```php
template(string $path, array $variables): string
```

Renders a PHP template file with the given variables.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | string | Absolute path to the template file |
| `$variables` | array | Associative array of variables to pass to template |

**Returns:** The rendered template as a string.

## Best Practices

| Practice | Description |
|----------|-------------|
| Use `htmlspecialchars()` | Always escape user data in HTML templates |
| Use `json_encode()` | Escape data in JSON templates |
| Absolute paths | Use `__DIR__` for reliable path resolution |
| Component structure | Split reusable parts into separate files |
| Keep logic minimal | Templates should focus on presentation |
