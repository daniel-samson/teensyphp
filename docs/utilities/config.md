---
sidebar_position: 1
---

# Configuration

## Basic Usage

```php
use TeensyPHP\Utility\Config;

// Get configuration value with default fallback
$dbEngine = Config::get('DATABASE_ENGINE', 'sqlite');
$dbHost = Config::get('DATABASE_HOST', 'localhost');
$debug = Config::get('APP_DEBUG', 'false');

// Use in your application
if ($debug === 'true') {
    error_reporting(E_ALL);
}
```

## Loading .env Files

```php
// public/index.php
use TeensyPHP\Utility\Config;

// Define app root (parent of public/)
define('APP_ROOT', dirname(__DIR__));

// Load environment variables from .env file
Config::loadEnvFile(APP_ROOT);

// Now use Config::get() anywhere in your app
$apiKey = Config::get('API_KEY');
```

## Example .env File

```ini
# Application
APP_NAME=MyApp
APP_DEBUG=true
APP_URL=http://localhost:8000

# MySQL Database
DATABASE_ENGINE=mysql
DATABASE_DATABASE=myapp
DATABASE_USERNAME=root
DATABASE_PASSWORD=secret
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306

# SQLite Database (alternative)
# DATABASE_ENGINE=sqlite
# DATABASE_DATABASE=../myapp.sqlite

# PostgreSQL Database (alternative)
# DATABASE_ENGINE=pgsql
# DATABASE_DATABASE=myapp
# DATABASE_USERNAME=postgres
# DATABASE_PASSWORD=secret
# DATABASE_HOST=127.0.0.1
# DATABASE_PORT=5432

# Logging
LOG_LEVEL=debug

# External Services
API_KEY=your-api-key-here
SMTP_HOST=smtp.mailtrap.io
SMTP_PORT=587
```

## Environment-Specific Configuration

```php
// Load different .env files based on environment
$env = getenv('APP_ENV') ?: 'development';

if ($env === 'production') {
    Config::loadEnvFile(APP_ROOT, '.env.production');
} elseif ($env === 'testing') {
    Config::loadEnvFile(APP_ROOT, '.env.testing');
} else {
    Config::loadEnvFile(APP_ROOT);
}
```

## How It Works

The `Config` class provides a simple way to access environment variables:

1. **Environment variables take priority** - If set in the system environment, those values are used
2. **`.env` file as fallback** - Values from `.env` are loaded when `loadEnvFile()` is called
3. **Default values** - The second parameter to `get()` provides a fallback if the variable isn't set

Never commit `.env` files to version control. Add `.env` to your `.gitignore`.

## Function Reference

### Config::get()

```php
Config::get(string $key, mixed $default = null): mixed
```

Retrieves a configuration value from environment variables.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$key` | string | Environment variable name |
| `$default` | mixed | Default value if not set (default: null) |

**Returns:** The environment variable value or the default.

### Config::loadEnvFile()

```php
Config::loadEnvFile(string $path, string $filename = '.env'): void
```

Loads environment variables from a file.

| Parameter | Type | Description |
|-----------|------|-------------|
| `$path` | string | Directory containing the .env file |
| `$filename` | string | Filename (default: '.env') |

## Common Configuration Keys

| Key | Description | Example |
|-----|-------------|---------|
| `DATABASE_ENGINE` | Database type | mysql, pgsql, sqlite, sqlsrv |
| `DATABASE_DATABASE` | Database name or file path | myapp, ../app.sqlite |
| `DATABASE_HOST` | Database server host | localhost, 127.0.0.1 |
| `DATABASE_PORT` | Database server port | 3306, 5432 |
| `DATABASE_USERNAME` | Database username | root, postgres |
| `DATABASE_PASSWORD` | Database password | secret |
| `LOG_LEVEL` | Minimum log level | debug, info, warning, error |
| `APP_DEBUG` | Enable debug mode | true, false |
