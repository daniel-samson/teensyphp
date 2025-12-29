---
sidebar_position: 2
---

# Database

## Quick Start

```php
// bootstrap.php
use TeensyPHP\Utility\Config;
use TeensyPHP\Utility\Database;
use App\Entity\BaseEntity;

// Initialize database connection
Database::connect(
    Config::get("DATABASE_ENGINE", "sqlite"),
    Config::get("DATABASE_DATABASE", __DIR__ . "/teensydb.sqlite"),
    Config::get("DATABASE_HOST"),
    Config::get("DATABASE_PORT"),
    Config::get("DATABASE_USERNAME"),
    Config::get("DATABASE_PASSWORD")
);

// Make connection available to entities
BaseEntity::$DB = Database::connection();

// Use the connection directly
$stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = ?');
$stmt->execute([1]);
$user = $stmt->fetch(\PDO::FETCH_ASSOC);
```

## Query Examples

```php
// Select all records
$stmt = Database::connection()->prepare('SELECT * FROM users');
$stmt->execute();
$users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Select with WHERE clause
$stmt = Database::connection()->prepare('SELECT * FROM users WHERE status = ?');
$stmt->execute(['active']);
$activeUsers = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Insert a record
$stmt = Database::connection()->prepare(
    'INSERT INTO users (name, email) VALUES (?, ?)'
);
$stmt->execute(['John Doe', 'john@example.com']);
$newId = Database::connection()->lastInsertId();

// Update a record
$stmt = Database::connection()->prepare(
    'UPDATE users SET name = ? WHERE id = ?'
);
$stmt->execute(['Jane Doe', 1]);

// Delete a record
$stmt = Database::connection()->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([1]);
```

## Named Parameters

```php
// Using named parameters for clarity
$stmt = Database::connection()->prepare(
    'SELECT * FROM orders WHERE user_id = :user_id AND status = :status'
);
$stmt->execute([
    ':user_id' => 42,
    ':status' => 'pending'
]);
$orders = $stmt->fetchAll(\PDO::FETCH_ASSOC);

// Insert with named parameters
$stmt = Database::connection()->prepare(
    'INSERT INTO users (name, email, created_at) VALUES (:name, :email, :created_at)'
);
$stmt->execute([
    ':name' => 'Alice',
    ':email' => 'alice@example.com',
    ':created_at' => date('Y-m-d H:i:s')
]);
```

## Transactions

```php
$pdo = Database::connection();

try {
    $pdo->beginTransaction();

    // Debit from one account
    $stmt = $pdo->prepare('UPDATE accounts SET balance = balance - ? WHERE id = ?');
    $stmt->execute([100, 1]);

    // Credit to another account
    $stmt = $pdo->prepare('UPDATE accounts SET balance = balance + ? WHERE id = ?');
    $stmt->execute([100, 2]);

    $pdo->commit();
    json_out(['success' => true]);

} catch (\Exception $e) {
    $pdo->rollBack();
    throw $e;
}
```

## Using with Routes

Database is initialized in `bootstrap.php`, so it's available in all routes:

```php
// routes/api.php
routerGroup("/api", function() {
    route(method(GET), url_path("/users"), function() {
        $stmt = Database::connection()->prepare('SELECT * FROM users');
        $stmt->execute();
        render(200, json_out($stmt->fetchAll(\PDO::FETCH_ASSOC)));
    });

    route(method(GET), url_path_params("/users/:id"), function() {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = ?');
        $stmt->execute([$_GET[':id']]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$user) {
            render(404, json_out(['error' => 'User not found']));
            return;
        }

        render(200, json_out($user));
    });
});
```

For most use cases, prefer using [Entities](/docs/utilities/entities) instead of raw queries.

## How It Works

The `Database` class is a thin wrapper around PDO:

1. **`connect()`** creates a PDO connection based on the database engine
2. **`connection()`** returns the PDO instance for direct use
3. All standard PDO methods are available (prepare, execute, fetch, etc.)

The connection is stored as a singleton, so calling `connection()` always returns the same PDO instance.

## Function Reference

### Database::connect()

```php
Database::connect(
    string $engine,
    string $database,
    ?string $host = null,
    ?string $port = null,
    ?string $username = null,
    ?string $password = null
): Database
```

Creates a database connection.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$engine` | string | Database engine (mysql, pgsql, sqlite, sqlsrv) |
| `$database` | string | Database name or file path |
| `$host` | string\|null | Database host |
| `$port` | string\|null | Database port |
| `$username` | string\|null | Database username |
| `$password` | string\|null | Database password |

### Database::connection()

```php
Database::connection(): \PDO
```

Returns the PDO connection instance.

## Supported Databases

| Engine | Value | Default Port |
|--------|-------|--------------|
| MySQL | `mysql` | 3306 |
| PostgreSQL | `pgsql` | 5432 |
| SQLite | `sqlite` | N/A |
| SQL Server | `sqlsrv` | 1433 |

## PDO Fetch Modes

| Mode | Description |
|------|-------------|
| `PDO::FETCH_ASSOC` | Associative array |
| `PDO::FETCH_OBJ` | Anonymous object |
| `PDO::FETCH_CLASS` | Instance of specified class |
| `PDO::FETCH_NUM` | Numeric array |
