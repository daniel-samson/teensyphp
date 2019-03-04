# Teensy PHP

The minimalists web framework

## Features
- Strongly typed
- Avoids object oriented programming encapsulation
- Favor arrays over objects
- Favor vanilla php over a library
- Easy to inject or replace functionality

## Installation

```composer require daniel-samson/teensyphp``` 


## Configuration

#### Apache

you need to add AllowOverride directive to your config
```apacheconfig
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html
        ErrorLog ${APACHE_LOG_DIR}/local_errors.log
        CustomLog ${APACHE_LOG_DIR}/local_access.log combined
        <Directory />
                Options FollowSymLinks
                AllowOverride None
        </Directory>
        <Directory /var/www/html>
                Options Indexes FollowSymLinks MultiViews
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>
</VirtualHost>
```

Create a .htaccess file in the your document root directory

```htaccess
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^(.*) index.php?url=$1 [L,QSA]
```

#### Nginx

```nginx
location / {
  if (!-e $request_filename){
    rewrite ^/(.*) /index.php?url=$1 break;
  }
}
```

## Routing

```php
<?php
// index.php
require_once __DIR__ . '/vendor/autoloader.php';

route(method(GET), url_path(''), function() { 
    render(200, content(JSON_CONTENT, json_encode(["hello" => "world"]))); 
});

route(method(GET), url_path_params('/admin/:page'), middleware(sso(), function() {
    render(200, json_out(["page" => $_GET['page']));
}));

// other wise
render(404, json_out([]));
```
