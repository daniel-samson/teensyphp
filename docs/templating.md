---
sidebar_position: 6
---

# Templating

## Basic Template

```php
// templates/pages/greeting.php
<h1>Hello, <?= htmlspecialchars($name) ?>!</h1>
<p>Welcome to our site.</p>
```

```php
// App/Actions/Home/DisplayGreeting.php
namespace App\Actions\Home;

class DisplayGreeting
{
    public function __invoke()
    {
        $html = template(app_root() . '/templates/pages/greeting.php', [
            'name' => 'Alice'
        ]);
        render(200, html_out($html));
    }
}
```

**Output:**
```html
<h1>Hello, Alice!</h1>
<p>Welcome to our site.</p>
```

## Page Layout with Components

```php
// templates/layouts/page_beginning.php
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - My Site</title>
    <link rel="stylesheet" href="/css/base.css">
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
// templates/layouts/page_end.php
    </main>
    <footer>
        <p>&copy; <?= date('Y') ?> My Site</p>
    </footer>
</body>
</html>
```

```php
// templates/pages/home.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Home"]); ?>

<h1>Welcome Home</h1>
<p>This is a TeensyPHP project.</p>

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

```php
// App/Actions/Home/DisplayHome.php
namespace App\Actions\Home;

class DisplayHome
{
    public function __invoke()
    {
        render(200, html_out(template(app_root() . "/templates/pages/home.php", [])));
    }
}
```

## Dynamic Lists

```php
// templates/components/user-list.php
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
// templates/pages/users.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Users"]); ?>

<h1>Users</h1>
<?= template(app_root() . "/templates/components/user-list.php", ["users" => $users]); ?>

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

```php
// App/Actions/User/ListUsers.php
namespace App\Actions\User;

use App\Entity\User;

class ListUsers
{
    public function __invoke()
    {
        $users = User::findAll();
        $usersArray = array_map(fn($u) => $u->toArray(), $users);

        $html = template(app_root() . '/templates/pages/users.php', [
            'users' => $usersArray
        ]);
        render(200, html_out($html));
    }
}
```

## Reusable Components

```php
// templates/components/table.php
<table>
    <thead>
        <tr>
            <?php foreach ($headers as $header): ?>
                <th><?= htmlspecialchars($header) ?></th>
            <?php endforeach; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
            <tr>
                <?php foreach ($row as $cell): ?>
                    <td><?= htmlspecialchars($cell) ?></td>
                <?php endforeach; ?>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
```

```php
// templates/pages/table-page.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Data"]); ?>

<h1>Data Table</h1>
<?= template(app_root() . "/templates/components/table.php", [
    "headers" => $headers,
    "rows" => $rows
]); ?>

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

## Error Pages

```php
// templates/pages/404.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Not Found"]); ?>

<h1>404 - Page Not Found</h1>
<p>The page you requested could not be found.</p>
<a href="/">Return Home</a>

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

```php
// templates/pages/500.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Error"]); ?>

<h1>500 - Internal Server Error</h1>
<p>Something went wrong. Please try again later.</p>
<a href="/">Return Home</a>

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
```

```php
// functions.php - exception handler uses these templates
function handle_exception(Throwable $exception): void
{
    if ($exception->getCode() === 404) {
        http_response_code(404);
        echo template(app_root() . "/templates/pages/404.php", []);
    } else {
        http_response_code(500);
        echo template(app_root() . "/templates/pages/500.php", []);
    }
}
```

## Conditional Content

```php
// templates/pages/dashboard.php
<?= template(app_root() . "/templates/layouts/page_beginning.php", ["title" => "Dashboard"]); ?>

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

<?= template(app_root() . "/templates/layouts/page_end.php", []); ?>
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

### app_root()

```php
app_root(): string
```

Returns the application root directory. Use this to build paths to templates.

## Template Directory Structure

| Directory | Purpose |
|-----------|---------|
| `templates/layouts/` | Page wrappers (header/footer) |
| `templates/pages/` | Full page templates |
| `templates/components/` | Reusable UI components |

## Best Practices

| Practice | Description |
|----------|-------------|
| Use `htmlspecialchars()` | Always escape user data in HTML templates |
| Use `json_encode()` | Escape data in JSON templates |
| Use `app_root()` | Use for reliable path resolution |
| Component structure | Split reusable parts into separate files |
| Keep logic minimal | Templates should focus on presentation |
