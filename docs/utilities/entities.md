---
sidebar_position: 3
---

# Entities

## Quick Start

```php
// Create a User entity
class User extends BaseEntity
{
    public static string $table = 'users';
    public int $id;
    public string $name;
    public string $email;
    public string $created_at;
}

// Usage
$user = User::find(1);
echo $user->name;  // "John Doe"

$users = User::findAll();
foreach ($users as $user) {
    echo $user->email;
}
```

## CRUD Operations

```php
// CREATE - Insert new record
$user = User::create([
    'name' => 'Alice',
    'email' => 'alice@example.com',
]);
echo $user->id;  // New ID

// READ - Find by ID
$user = User::find(1);

// READ - Find all records
$users = User::findAll();

// UPDATE - Modify existing record
$user = User::update([
    'name' => 'Alice Smith',
    'email' => 'alice.smith@example.com',
], 1);

// DELETE - Remove record
User::delete(1);
```

## Setting Up Entities

**1. Create a BaseEntity class:**

```php
// src/BaseEntity.php
use TeensyPHP\Traits\Crud;
use TeensyPHP\Traits\ArrayAccessImplementation;

class BaseEntity implements \ArrayAccess
{
    use ArrayAccessImplementation, Crud;
}
```

**2. Initialize the database connection:**

```php
// public/index.php
use TeensyPHP\Utility\Config;
use TeensyPHP\Utility\Database;

Config::loadEnvFile(APP_ROOT);

BaseEntity::$DB = Database::connect(
    Config::get('DATABASE_ENGINE', 'sqlite'),
    Config::get('DATABASE_DATABASE', '../app.sqlite'),
    Config::get('DATABASE_HOST'),
    Config::get('DATABASE_PORT'),
    Config::get('DATABASE_USERNAME'),
    Config::get('DATABASE_PASSWORD')
)->connection();
```

**3. Create entity classes:**

```php
// src/Entities/User.php
class User extends BaseEntity
{
    public static string $table = 'users';

    public int $id;
    public string $name;
    public string $email;
    public ?string $avatar = null;
    public string $created_at;
    public string $updated_at;
}

// src/Entities/Post.php
class Post extends BaseEntity
{
    public static string $table = 'posts';

    public int $id;
    public int $user_id;
    public string $title;
    public string $content;
    public string $status;
    public string $created_at;
}
```

## Working with Request Data

```php
// Create entity from request body
route(method(POST), url_path('/api/users'), function() {
    $data = json_in();
    $user = User::create($data);
    render(201, json_out($user->toArray()));
});

// Make entity without saving (for validation)
route(method(POST), url_path('/api/users'), function() {
    $data = json_in();
    $user = User::make($data);

    // Validate before saving
    if (empty($user->email)) {
        render(400, json_out(['error' => 'Email required']));
        return;
    }

    $saved = User::create($data);
    render(201, json_out($saved->toArray()));
});
```

## Array Access

```php
// Entities support array access
$user = User::find(1);

// Access as array
echo $user['name'];
echo $user['email'];

// Convert to array
$array = $user->toArray();
// ['id' => 1, 'name' => 'John', 'email' => 'john@example.com', ...]

// Useful for JSON output
render(200, json_out($user->toArray()));

// Or output multiple entities
$users = User::findAll();
$output = array_map(fn($u) => $u->toArray(), $users);
render(200, json_out($output));
```

## Complete API Example

```php
router(function() {
    // Setup
    BaseEntity::$DB = Database::connect(...)->connection();

    // List all users
    route(method(GET), url_path('/api/users'), function() {
        $users = User::findAll();
        $output = array_map(fn($u) => $u->toArray(), $users);
        render(200, json_out($output));
    });

    // Get single user
    route(method(GET), url_path_params('/api/users/:id'), function() {
        $user = User::find($_GET[':id']);
        if (!$user) {
            render(404, json_out(['error' => 'Not found']));
            return;
        }
        render(200, json_out($user->toArray()));
    });

    // Create user
    route(method(POST), url_path('/api/users'), function() {
        $data = json_in();
        $user = User::create($data);
        render(201, json_out($user->toArray()));
    });

    // Update user
    route(method(PUT), url_path_params('/api/users/:id'), function() {
        $data = json_in();
        $user = User::update($data, $_GET[':id']);
        render(200, json_out($user->toArray()));
    });

    // Delete user
    route(method(DELETE), url_path_params('/api/users/:id'), function() {
        User::delete($_GET[':id']);
        render(204, '');
    });
});
```

## How It Works

The `Crud` trait provides simple database operations:

1. **Entity properties map to columns** - Each public property on the entity corresponds to a database column
2. **`$table` defines the table name** - Set `public static string $table` to specify the database table
3. **`$DB` holds the PDO connection** - Set once on the base entity class
4. **Array access via trait** - `ArrayAccessImplementation` enables `$entity['field']` syntax

## Function Reference

### Entity::find()

```php
Entity::find(int|string $id): ?static
```

Finds a record by primary key (assumes `id` column).

### Entity::findAll()

```php
Entity::findAll(): array
```

Returns all records from the table.

### Entity::create()

```php
Entity::create(array $data): static
```

Inserts a new record and returns the entity with the new ID.

### Entity::update()

```php
Entity::update(array $data, int|string $id): static
```

Updates a record by ID and returns the updated entity.

### Entity::delete()

```php
Entity::delete(int|string $id): void
```

Deletes a record by ID.

### Entity::make()

```php
Entity::make(array $data): static
```

Creates an entity instance from data without saving to database.

### Entity::toArray()

```php
$entity->toArray(): array
```

Converts the entity to an associative array.

## Required Setup

| Item | Description |
|------|-------------|
| `BaseEntity::$DB` | PDO connection instance |
| `Entity::$table` | Database table name |
| Entity properties | Public properties matching column names |
