---
sidebar_position: 3
---

# Entities

TeensyPHP provides a `Crud` trait with common database functions for entities.

## Base Entity

Create a base class to share functionality:

```php
use TeensyPHP\Traits\Crud;
use TeensyPHP\Traits\ArrayAccessImplementation;

class BaseEntity
{
    use ArrayAccessImplementation, Crud;
}
```

Set the database connection in your entry point:

```php
BaseEntity::$DB = Database::connect(
    Config::get("DATABASE_ENGINE", "sqlite"),
    Config::get("DATABASE_DATABASE", "app.sqlite"),
    Config::get("DATABASE_HOST"),
    Config::get("DATABASE_PORT"),
    Config::get("DATABASE_USERNAME"),
    Config::get("DATABASE_PASSWORD"),
)->connection();
```

## Creating Entities

```php
class User extends BaseEntity
{
    public static string $table = 'users';
    public int $id;
    public string $name;
}
```

> Add each database column as a property of the entity.

## CRUD Operations

### Make

Create an entity from an array:

```php
$userFromRequest = User::make($_POST);
```

### Read

```php
$user = User::find(1);
$users = User::findAll();
```

### Create

```php
$user = User::create([
    'name' => 'John Doe',
    'email' => 'john@doe.com',
]);
```

### Update

```php
$user = User::update([
    'name' => 'Jane Doe',
    'email' => 'jane@doe.com',
], 1);
```

### Delete

```php
User::delete(1);
```

## Array Access

Entities support array access and include `toArray()`:

```php
$user = User::find(1);
echo $user['name'];
$array = $user->toArray();
```
