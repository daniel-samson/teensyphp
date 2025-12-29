---
sidebar_position: 2
---

# Database

TeensyPHP supports MySQL, PostgreSQL, SQLite, and MSSQL.

## Initializing the Database

```php
use TeensyPHP\Utility\Database;

$db = Database::connect(
    Config::get("DATABASE_ENGINE", "sqlite"),
    Config::get("DATABASE_DATABASE", "app.sqlite"),
    Config::get("DATABASE_HOST"),
    Config::get("DATABASE_PORT"),
    Config::get("DATABASE_USERNAME"),
    Config::get("DATABASE_PASSWORD"),
);
```

## Using the Database

Get the PDO connection:

```php
$sql = "SELECT * FROM {$table} WHERE id = ?";
$statement = Database::connection()->prepare($sql);
$result = $statement->execute([$id]);
$records = $statement->fetchAll(\PDO::FETCH_ASSOC);
```
