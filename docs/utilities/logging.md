---
sidebar_position: 4
---

# Logging

TeensyPHP provides a `Log` class filtered by the `LOG_LEVEL` environment variable.

## Usage

```php
use TeensyPHP\Utility\Log;

Log::debug("message");
Log::info("message");
Log::warning("message");
Log::error("message");
Log::critical("message");
Log::alert("message");
Log::emergency("message");
```

## Configuration

Set the log level in your `.env` file:

```ini
LOG_LEVEL=debug
```
