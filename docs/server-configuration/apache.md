---
sidebar_position: 2
---

# Apache

## Quick Setup

**1. Create `.htaccess` in your document root (`public/`):**

```htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*) index.php?url=$1 [L,QSA]
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

**3. Ensure mod_rewrite is enabled:**

```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

## Virtual Host Configuration

```apacheconf
<VirtualHost *:80>
    ServerName myapp.local
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/myapp/public

    ErrorLog ${APACHE_LOG_DIR}/myapp-error.log
    CustomLog ${APACHE_LOG_DIR}/myapp-access.log combined

    <Directory /var/www/myapp/public>
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

## HTTPS Virtual Host

```apacheconf
<VirtualHost *:443>
    ServerName myapp.com
    DocumentRoot /var/www/myapp/public

    SSLEngine on
    SSLCertificateFile /etc/ssl/certs/myapp.crt
    SSLCertificateKeyFile /etc/ssl/private/myapp.key

    <Directory /var/www/myapp/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/myapp-error.log
    CustomLog ${APACHE_LOG_DIR}/myapp-access.log combined
</VirtualHost>
```

## How It Works

The `.htaccess` file uses mod_rewrite to:
1. Check if the requested file/directory exists
2. If not, rewrite the URL to `index.php?url=<path>`
3. TeensyPHP reads `$_GET['url']` to determine the route

This allows clean URLs like `/api/users` instead of `/index.php?url=api/users`.

## Configuration Options

| Directive | Purpose |
|-----------|---------|
| `RewriteEngine on` | Enable URL rewriting |
| `RewriteCond %{REQUEST_FILENAME} !-d` | Skip if directory exists |
| `RewriteCond %{REQUEST_FILENAME} !-f` | Skip if file exists |
| `RewriteRule ^(.*) index.php?url=$1 [L,QSA]` | Rewrite to index.php |
| `AllowOverride All` | Allow .htaccess to override settings |
| `Require all granted` | Allow access (Apache 2.4+) |

## Troubleshooting

| Issue | Solution |
|-------|----------|
| 500 Internal Server Error | Check if mod_rewrite is enabled |
| 403 Forbidden | Verify `AllowOverride All` is set |
| Routes not working | Ensure `.htaccess` is in document root |
| Static files not loading | Check `RewriteCond` rules |
