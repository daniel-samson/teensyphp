---
sidebar_position: 3
---

# Request & Response

## JSON API Example

```php
router(function() {
    // Return JSON data
    route(method(GET), url_path("/api/users"), function() {
        $users = [
            ["id" => 1, "name" => "Alice"],
            ["id" => 2, "name" => "Bob"],
        ];
        render(200, json_out($users));
    });

    // Accept JSON input
    route(method(POST), url_path("/api/users"), function() {
        $data = json_in();
        // $data contains the parsed JSON body
        render(201, json_out(["created" => $data]));
    });

    // Read request headers
    route(method(GET), url_path("/api/protected"), function() {
        $token = request_header("Authorization");
        if (empty($token)) {
            render(401, json_out(["error" => "Unauthorized"]));
            return;
        }
        render(200, json_out(["message" => "Welcome!"]));
    });
});
```

## Content Negotiation

```php
route(method(GET), url_path("/"), function() {
    // Check Accept header and respond accordingly
    if (accept(JSON_CONTENT)) {
        render(200, json_out(["hello" => "world"]));
        return;
    }

    if (accept(HTML_CONTENT)) {
        render(200, html_out("<h1>Hello World</h1>"));
        return;
    }

    if (accept(XML_CONTENT)) {
        render(200, content(XML_CONTENT, "<message>Hello World</message>"));
        return;
    }

    throw new Exception("Not Acceptable", 406);
});
```

## Custom Headers

```php
route(method(GET), url_path("/download"), function() {
    $fileContent = file_get_contents("report.pdf");

    render(200, content(PDF_CONTENT, $fileContent), [
        "Content-Disposition" => "attachment; filename=\"report.pdf\"",
        "Cache-Control" => "no-cache",
    ]);
});

route(method(GET), url_path("/api/data"), function() {
    // Set individual response headers
    response_header("X-Custom-Header", "custom-value");
    response_header("Cache-Control", "max-age=3600");

    render(200, json_out(["data" => "value"]));
});
```

## Redirects

```php
route(method(GET), url_path("/old-page"), function() {
    redirect("/new-page");
});

route(method(POST), url_path("/form-submit"), function() {
    $data = $_POST;
    // Process form...
    redirect("/thank-you");
});
```

## How It Works

TeensyPHP provides simple functions for handling HTTP requests and responses:

- **Request functions** read incoming data (headers, JSON body)
- **Response functions** send data back with appropriate content types and status codes
- **Content negotiation** lets you respond with different formats based on the client's Accept header

The `render()` function is the primary way to send responses, combining status code, content, and optional headers.

## Function Reference

### Request Functions

| Function | Description |
|----------|-------------|
| `request_header(string $header): ?string` | Get a request header value |
| `json_in(): array` | Parse JSON request body |
| `accept(string $contentType): bool` | Check if client accepts content type |

### Response Functions

| Function | Description |
|----------|-------------|
| `render(int $code, string $content, array $headers = []): void` | Send response with status code and content |
| `json_out(array $data): string` | Encode array as JSON string |
| `html_out(string $content): string` | Wrap content with HTML content type |
| `content(string $type, string $content): string` | Set content type and return content |
| `redirect(string $url): void` | Send redirect response |
| `response_header(string $header, string $value): void` | Set a response header |

## Content Type Constants

| Constant | Content Type |
|----------|--------------|
| `JSON_CONTENT` | application/json |
| `HTML_CONTENT` | text/html |
| `XML_CONTENT` | application/xml |
| `TEXT_CONTENT` | text/plain |
| `CSS_CONTENT` | text/css |
| `JAVASCRIPT_CONTENT` | application/javascript |
| `PDF_CONTENT` | application/pdf |
| `ATOM_CONTENT` | application/atom+xml |

## HTTP Status Codes

Common status codes used with `render()`:

| Code | Meaning |
|------|---------|
| 200 | OK |
| 201 | Created |
| 204 | No Content |
| 301 | Moved Permanently |
| 302 | Found (Redirect) |
| 400 | Bad Request |
| 401 | Unauthorized |
| 403 | Forbidden |
| 404 | Not Found |
| 406 | Not Acceptable |
| 500 | Internal Server Error |
