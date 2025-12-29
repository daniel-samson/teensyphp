---
sidebar_position: 3
---

# Entities

## Quick Start

```php
// App/Entity/User.php
namespace App\Entity;

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
use App\Entity\User;

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

**1. The BaseEntity class (already included in new projects):**

```php
// App/Entity/BaseEntity.php
namespace App\Entity;

use TeensyPHP\Traits\Crud;
use TeensyPHP\Traits\ArrayAccessImplementation;

class BaseEntity
{
    use ArrayAccessImplementation, Crud;
}
```

**2. Database connection in bootstrap.php:**

```php
// bootstrap.php
use TeensyPHP\Utility\Config;
use TeensyPHP\Utility\Database;
use App\Entity\BaseEntity;

require_once __DIR__ . "/vendor/autoload.php";

Config::loadEnvFile(app_root());

BaseEntity::$DB = Database::connect(
    Config::get("DATABASE_ENGINE", "sqlite"),
    Config::get("DATABASE_DATABASE", __DIR__ . "/teensydb.sqlite"),
    Config::get("DATABASE_HOST"),
    Config::get("DATABASE_PORT"),
    Config::get("DATABASE_USERNAME"),
    Config::get("DATABASE_PASSWORD"),
)->connection();
```

**3. Create entity classes:**

```php
// App/Entity/User.php
namespace App\Entity;

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

// App/Entity/Post.php
namespace App\Entity;

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

## Using Entities in Actions

```php
// App/Actions/User/CreateUser.php
namespace App\Actions\User;

use App\Entity\User;

class CreateUser
{
    public function __invoke()
    {
        $data = json_in();
        $user = User::create($data);
        render(201, json_out($user->toArray()));
    }
}

// App/Actions/User/ShowUser.php
namespace App\Actions\User;

use App\Entity\User;
use TeensyPHP\Exceptions\TeensyPHPException;

class ShowUser
{
    public function __invoke()
    {
        $user = User::find($_GET[':id']);

        if (!$user) {
            TeensyPHPException::throwNotFound();
        }

        render(200, json_out($user->toArray()));
    }
}
```

```php
// routes/api.php
use App\Actions\User\CreateUser;
use App\Actions\User\ShowUser;

routerGroup("/api", function() {
    route(method(POST), url_path("/users"), CreateUser::class);
    route(method(GET), url_path_params("/users/:id"), ShowUser::class);
});
```

## Array Access

```php
use App\Entity\User;

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
// App/Actions/User/ListUsers.php
namespace App\Actions\User;

use App\Entity\User;

class ListUsers
{
    public function __invoke()
    {
        $users = User::findAll();
        $output = array_map(fn($u) => $u->toArray(), $users);
        render(200, json_out($output));
    }
}

// App/Actions/User/ShowUser.php
namespace App\Actions\User;

use App\Entity\User;
use TeensyPHP\Exceptions\TeensyPHPException;

class ShowUser
{
    public function __invoke()
    {
        $user = User::find($_GET[':id']);
        if (!$user) {
            TeensyPHPException::throwNotFound();
        }
        render(200, json_out($user->toArray()));
    }
}

// App/Actions/User/CreateUser.php
namespace App\Actions\User;

use App\Entity\User;

class CreateUser
{
    public function __invoke()
    {
        $data = json_in();
        $user = User::create($data);
        render(201, json_out($user->toArray()));
    }
}

// App/Actions/User/UpdateUser.php
namespace App\Actions\User;

use App\Entity\User;

class UpdateUser
{
    public function __invoke()
    {
        $data = json_in();
        $user = User::update($data, $_GET[':id']);
        render(200, json_out($user->toArray()));
    }
}

// App/Actions/User/DeleteUser.php
namespace App\Actions\User;

use App\Entity\User;

class DeleteUser
{
    public function __invoke()
    {
        User::delete($_GET[':id']);
        render(204, '');
    }
}
```

```php
// routes/api.php
use App\Actions\User\ListUsers;
use App\Actions\User\ShowUser;
use App\Actions\User\CreateUser;
use App\Actions\User\UpdateUser;
use App\Actions\User\DeleteUser;

routerGroup("/api", function() {
    route(method(GET), url_path("/users"), ListUsers::class);
    route(method(GET), url_path_params("/users/:id"), ShowUser::class);
    route(method(POST), url_path("/users"), CreateUser::class);
    route(method(PUT), url_path_params("/users/:id"), UpdateUser::class);
    route(method(DELETE), url_path_params("/users/:id"), DeleteUser::class);
});
```

## How It Works

The `Crud` trait provides simple database operations:

1. **Entity properties map to columns** - Each public property on the entity corresponds to a database column
2. **`$table` defines the table name** - Set `public static string $table` to specify the database table
3. **`$DB` holds the PDO connection** - Set once on the base entity class in bootstrap.php
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

## Entity Location

| Path | Purpose |
|------|---------|
| `App/Entity/BaseEntity.php` | Base class with Crud trait |
| `App/Entity/*.php` | Your entity classes |
