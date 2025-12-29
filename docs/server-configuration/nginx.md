---
sidebar_position: 3
---

# Nginx

## Quick Setup

**1. Create Nginx server block:**

```nginx
server {
    listen 80;
    server_name myapp.local;
    root /var/www/myapp/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

**2. Create your entry point (`public/index.php`):**

```php
router(function() {
    route(method(GET), url_path("/"), function() {
        json_out(["message" => "Hello World"]);
    });

    route(method(GET), url_path("/api/users"), function() {
        json_out(["users" => []]);
    });
});
```

**3. Enable the site and restart Nginx:**

```bash
sudo ln -s /etc/nginx/sites-available/myapp /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

## Full Production Configuration

```nginx
user nginx;
worker_processes auto;
error_log /var/log/nginx/error.log;
pid /run/nginx.pid;

events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    sendfile on;
    keepalive_timeout 65;

    gzip on;
    gzip_types text/plain text/css application/javascript application/json image/svg+xml;

    server {
        listen 80;
        server_name myapp.com www.myapp.com;
        root /var/www/myapp/public;
        index index.php;

        # Rewrite all requests to index.php
        location / {
            try_files $uri $uri/ /index.php?url=$uri&$args;
        }

        # Handle PHP files
        location ~ \.php$ {
            fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
            fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
            include fastcgi_params;
        }

        # Deny access to hidden files
        location ~ /\. {
            deny all;
        }

        # Custom error pages
        error_page 404 /index.php?url=404;
        error_page 500 502 503 504 /index.php?url=500;

        access_log /var/log/nginx/myapp-access.log;
        error_log /var/log/nginx/myapp-error.log;
    }
}
```

## HTTPS Configuration

```nginx
server {
    listen 443 ssl http2;
    server_name myapp.com;
    root /var/www/myapp/public;
    index index.php;

    ssl_certificate /etc/ssl/certs/myapp.crt;
    ssl_certificate_key /etc/ssl/private/myapp.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location / {
        try_files $uri $uri/ /index.php?url=$uri&$args;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}

# Redirect HTTP to HTTPS
server {
    listen 80;
    server_name myapp.com;
    return 301 https://$server_name$request_uri;
}
```

## How It Works

Nginx uses `try_files` to:
1. Check if the requested file exists (`$uri`)
2. Check if the requested directory exists (`$uri/`)
3. Fall back to `index.php?url=$uri&$args`

TeensyPHP reads `$_GET['url']` to determine the route.

## Configuration Options

| Directive | Purpose |
|-----------|---------|
| `try_files` | Check files before rewriting |
| `fastcgi_pass` | PHP-FPM socket or address |
| `root` | Document root (usually `public/`) |
| `index` | Default file to serve |
| `gzip on` | Enable compression |

## PHP-FPM Sockets

| PHP Version | Socket Path |
|-------------|-------------|
| PHP 8.0 | `/var/run/php/php8.0-fpm.sock` |
| PHP 8.1 | `/var/run/php/php8.1-fpm.sock` |
| PHP 8.2 | `/var/run/php/php8.2-fpm.sock` |
| PHP 8.3 | `/var/run/php/php8.3-fpm.sock` |

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 502 Bad Gateway | Check PHP-FPM is running |
| 404 Not Found | Verify `root` path is correct |
| Permission denied | Check file ownership (`www-data`) |
| Routes not matching | Ensure `try_files` includes `?url=$uri` |
