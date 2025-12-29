---
sidebar_position: 3
---

# Nginx

Pass the URL query param into your index.php:

```nginx
user  nginx;
worker_processes  auto;

events {
    worker_connections 1024;
}

http {
    include       mime.types;
    default_type  application/octet-stream;

    sendfile        on;
    keepalive_timeout  65;

    gzip on;
    gzip_types text/plain text/css application/javascript application/json image/svg+xml;

    server {
        listen 80;
        server_name example.com www.example.com;

        root /var/www/html/public;
        index index.html;

        location / {
            if (!-e $request_filename){
                rewrite ^/(.*) /index.php?url=$1 break;
            } 
        }

        error_page 404 /404.html;
        location = /404.html {
            internal;
        }

        access_log /var/log/nginx/access.log;
        error_log  /var/log/nginx/error.log;
    }
}
```
