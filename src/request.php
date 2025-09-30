<?php
function request_header(string $header): string
{
    // Handle CLI/test environment where getallheaders() might not work
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
        if (key_exists($header, $headers)) {
            return $headers[$header];
        }
    }
    
    // Fallback to $_SERVER for CLI/test environment
    $server_key = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
    if (isset($_SERVER[$server_key])) {
        return $_SERVER[$server_key];
    }

    return "";
}

function accept(string $content_type): bool
{
    return strpos(request_header('Accept'), $content_type) !== false;
}

function request_body(): string
{
    return file_get_contents("php://input");
}

function json_in(): array
{
    return json_decode(request_body(), true);
}
