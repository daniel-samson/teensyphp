---
sidebar_position: 3
---

# Request & Response

TeensyPHP provides functions to control how your application handles requests and responses.

## Request Headers

```php
$header = request_header(string $header);
```

## Response Headers

```php
response_header(string $header, string $value);
```

## Content Negotiation

Filter based on the Accept header:

```php
accept(string $content_type): bool
```

## Sending Responses

### Render

```php
render(int $http_code, string $content, array $headers): void
```

### Content Type

```php
content(string $type, string $content): string
```

### HTML Output

```php
html_out(string $content): string
```

### Redirect

```php
redirect(string $url)
```

## JSON API

### Reading JSON Input

```php
$body = json_in(): array
```

### JSON Output

```php
json_out(array $array): string
```

## Content Type Constants

```php
content(ATOM_CONTENT, $content);
content(CSS_CONTENT, $content);
content(JAVASCRIPT_CONTENT, $content);
content(JSON_CONTENT, $content);
content(PDF_CONTENT, $content);
content(TEXT_CONTENT, $content);
content(HTML_CONTENT, $content);
content(XML_CONTENT, $content);
```

## Example

```php
route(method(GET), url_path("/"), function() {
    if (accept(JSON_CONTENT)) {
        render(200, json_out(["hello" => "world"]));
        return;
    }
    
    if (accept(HTML_CONTENT)) {
        render(200, html_out(template(__DIR__."/template/home.php", [])));
        return;
    }

    throw new Exception("Not Acceptable", 406);
});

route(method(POST), url_path("/echo"), function() {
    if (accept(JSON_CONTENT)) {
        $body = json_in();
        render(200, json_out(["hello" => "world"]));
        return;
    }
   
    throw new Exception("Not Acceptable", 406);
});
```
