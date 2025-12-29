---
sidebar_position: 1
---

# Configuration

The `Config` class retrieves configuration values from environment variables.

```php
Config::get('DATABASE_ENGINE', 'sqlite');
```

## Using .env Files

Create a `.env` file during development:

```ini
# mysql / pgsql / sqlsrv
DATABASE_ENGINE=mysql
DATABASE_DATABASE=myapp
DATABASE_USERNAME=root
DATABASE_PASSWORD=secret
DATABASE_HOST=127.0.0.1
DATABASE_PORT=3306

# sqlite
# DATABASE_ENGINE=sqlite
# DATABASE_DATABASE=myapp.sqlite
```

Load the `.env` file in your entry point:

```php
Config::loadEnvFile(APP_ROOT);
```
